<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Rats\Zkteco\Lib\ZKTeco;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user')->latest('timestamp')->paginate(20);
        return view('admin.attendance.index', compact('attendances'));
    }

    public function sync(Request $request)
    {
        $ip = $request->input('device_ip', '192.168.1.201'); // Default ZKTeco Port 4370
        
        try {
            $zk = new ZKTeco($ip);
            if ($zk->connect()){
                $zk->disableDevice();
                
                $attendanceLog = $zk->getAttendance(); // Returns array
                
                $zk->enableDevice();
                $zk->disconnect();

                $newRecords = 0;

                foreach($attendanceLog as $log){
                    // Find user by biometric ID mapping
                    /** @var \App\Models\User|null $user */
                    $user = User::query()->where('biometric_id', $log['id'])->first();
                    
                    // Prevent duplicate logs based on identical biometric ID and timestamp
                    $existing = Attendance::where('biometric_id', $log['id'])
                        ->where('timestamp', $log['timestamp'])
                        ->first();
                        
                    if(!$existing) {
                        Attendance::create([
                            'user_id' => $user ? $user->id : null,
                            'biometric_id' => $log['id'],
                            'timestamp' => Carbon::parse($log['timestamp']),
                            'type' => $this->mapAttendanceType($log['type']),
                        ]);
                        $newRecords++;
                    }
                }
                
                return redirect()->route('admin.attendance.index')->with('success', "Synced successfully! {$newRecords} new attendance records found.");
            } else {
                return redirect()->route('admin.attendance.index')->with('error', "Could not connect to biometric device at {$ip}. Check network connection.");
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.attendance.index')->with('error', "Error communicating with ZKTeco: " . $e->getMessage());
        }
    }
    
    // ZKTeco standard state mappings
    private function mapAttendanceType($type)
    {
        switch($type) {
            case 0: return 'check_in';
            case 1: return 'check_out';
            case 2: return 'break_out';
            case 3: return 'break_in';
            case 4: return 'overtime_in';
            case 5: return 'overtime_out';
            default: return 'unknown';
        }
    }
    
    // Clear device logs (Admin only!)
    public function clearDevice(Request $request) {
        $ip = $request->input('device_ip', '192.168.1.201');
        
        try {
            $zk = new ZKTeco($ip);
            if ($zk->connect()){
                $zk->clearAttendance();
                $zk->disconnect();
                return redirect()->route('admin.attendance.index')->with('success', "Device attendance memory wiped successfully.");
            }
            return redirect()->route('admin.attendance.index')->with('error', "Could not connect to biometric device at {$ip}.");
        } catch (\Exception $e) {
            return redirect()->route('admin.attendance.index')->with('error', "Error: " . $e->getMessage());
        }
    }
}
