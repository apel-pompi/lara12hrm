<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //users
            [
                'group_name' => 'Users',
                'permissions' => [
                    'user.index',
                    'users.trash',
                    'user.create',
                    'user.store',
                    'user.show',
                    'user.edit',
                    'user.update',
                    'user.destroy',
                    'users.parmanentDelete',
                    'users.restore',
                ],
            ],
            //profiles
            [
                'group_name' => 'Profile',
                'permissions' => [
                    'profile.edit',
                    'profile.update',
                    'profile.destroy',
                ],
            ],
            //Roles
            [
                'group_name' => 'Role',
                'permissions' => [
                    'role.index',
                    'role.trash',
                    'role.create',
                    'role.store',
                    'role.show',
                    'role.edit',
                    'role.update',
                    'role.destroy',
                    'role.parmanentDelete',
                    'role.restore',
                    
                ],
            ],
            //branch            
            [
                'group_name' => 'Branch',
                'permissions' => [
                    'branch.index',
                    'branch.trash',
                    'branch.create',
                    'branch.store',
                    'branch.show',
                    'branch.edit',
                    'branch.update',
                    'branch.destroy',
                    'branch.parmanentDelete',
                    'branch.restore',
                    
                ],
            ],
            //Department            
            [
                'group_name' => 'Department',
                'permissions' => [
                    'department.index',
                    'department.trash',
                    'department.create',
                    'department.store',
                    'department.show',
                    'department.edit',
                    'department.update',
                    'department.destroy',
                    'department.parmanentDelete',
                    'department.restore',
                    
                ],
            ],
            //Designation            
            [
                'group_name' => 'Designation',
                'permissions' => [
                    'designation.index',
                    'designation.trash',
                    'designation.create',
                    'designation.store',
                    'designation.show',
                    'designation.edit',
                    'designation.update',
                    'designation.destroy',
                    'designation.parmanentDelete',
                    'designation.restore',
                    
                ],
            ],
            //Leave Plan            
            [
                'group_name' => 'LeavePlan',
                'permissions' => [
                    'leaveplan.index',
                    'leaveplan.trash',
                    'leaveplan.create',
                    'leaveplan.store',
                    'leaveplan.show',
                    'leaveplan.edit',
                    'leaveplan.update',
                    'leaveplan.destroy',
                    'leaveplan.parmanentDelete',
                    'leaveplan.restore',
                    
                ],
            ],
            //Holiday Header          
            [
                'group_name' => 'HolidayHeader',
                'permissions' => [
                    'holiday.index',
                    'holiday.approve',
                    'holiday.trash',
                    'holiday.create',
                    'holiday.store',
                    'holiday.show',
                    'holiday.edit',
                    'holiday.update',
                    'holiday.destroy',
                    'holiday.parmanentDelete',
                    'holiday.restore',
                    
                ],
            ],
            //Holiday Details          
            [
                'group_name' => 'HolidayDetails',
                'permissions' => [
                    'holidaydt.create',
                    'holidaydt.store',
                    'holidaydt.edit',
                    'holidaydt.update',
                    'holidaydt.destroy',
                    
                ],
            ],
            //Attendance Setting         
            [
                'group_name' => 'AttendanceSetting',
                'permissions' => [
                    'attendanmst.index',
                    'attendanmst.approve',
                    'attendanmst.trash',
                    'attendanmst.create',
                    'attendanmst.store',
                    'attendanmst.show',
                    'attendanmst.edit',
                    'attendanmst.update',
                    'attendanmst.destroy',
                    'attendanmst.parmanentDelete',
                    'attendanmst.restore',
                    
                ],
            ],
            //Attendance   
            [
                'group_name' => 'All Attendance',
                'permissions' => [
                    'attendance.index'
                    
                ],
            ],
            //All Attendance Status      
            [
                'group_name' => 'All Status',
                'permissions' => [
                    'attenstatus.index',
                    'attenstatus.store',
                    'attenstatus.show',
                    'attenstatus.edit',
                    'attenstatus.update',
                    'attenstatus.destroy'
                    
                ],
            ],
            //Employee Attendance Status      
            [
                'group_name' => 'Employee Status',
                'permissions' => [
                    'employeestatus.index',
                    
                ],
            ],
            //Personal Information        
            [
                'group_name' => 'PersonalInformation',
                'permissions' => [
                    'personal.index',
                    'personal.trash',
                    'personal.create',
                    'personal.store',
                    'personal.createAttendacne',
                    'personal.show',
                    'personal.edit',
                    'personal.update',
                    'personal.destroy',
                    'personal.parmanentDelete',
                    'personal.restore',
                    
                ],
            ],
            //Device      
            [
                'group_name' => 'Device',
                'permissions' => [
                    'device.index',
                    'device.trash',
                    'device.create',
                    'device.store',
                    'device.show',
                    'device.edit',
                    'device.update',
                    'device.destroy',
                    'device.parmanentDelete',
                    'device.restore',
                    
                ],
            ],
            
            //Reports    
            [
                'group_name' => 'Reports',
                'permissions' => [
                    'reports.PersonalProfile',
                    'reports.PersonalProfileReports',
                    'reports.dailyattendance',
                    'reports.dailyattendancereport',
                    'reports.employeeattendance',
                    'reports.employeeattendancereport',
                    'reports.monthlyattendance',
                    'reports.monthlyattendancereport'
                    
                ],
            ],
        ];
        $admin = User::where('username', 'superadmin')->first();
        $roleSuperAdmin = $this->CreateSuperAdminRole($admin);
        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                $permissionExist = Permission::where('name', $permissions[$i]['permissions'][$j])->first();
                if (is_null($permissionExist)) {
                    $permission = Permission::create(
                        [
                            'name' => $permissions[$i]['permissions'][$j],
                            'group_name' => $permissionGroup,
                            'guard_name' => 'web'
                        ]
                    );
                    $roleSuperAdmin->givePermissionTo($permission);
                    $permission->assignRole($roleSuperAdmin);
                }
            }
        }
        // Assign super admin role permission to superadmin user
        if ($admin) {
            $admin->assignRole($roleSuperAdmin);
        }
    }
    private function CreateSuperAdminRole($admin): Role
    {
        if (is_null($admin)) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        }else{
            $roleSuperAdmin = Role::where('name', 'superadmin')->where('guard_name', 'web')->first();
        }

        if (is_null($roleSuperAdmin)) {
            $roleSuperAdmin = Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        }

        return $roleSuperAdmin;
    }
}
