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
            
            //profiles
            [
                'group_name' => 'Profile',
                'permissions' => [
                    'profile.edit',
                    'profile.update',
                ],
            ],
            //Password
            [
                'group_name' => 'User Password',
                'permissions' => [
                    'password.edit',
                    'password.update',
                ],
            ],
            //Roles
            [
                'group_name' => 'Role',
                'permissions' => [
                    'role.index',
                    'role.store',
                    'role.edit',
                    'role.update',
                    'role.destroy',
                    
                ],
            ],
            //Parmission
            [
                'group_name' => 'Parmission',
                'permissions' => [
                    'user.index',
                    'user.store',
                    'user.edit',
                    'user.update',
                    'user.destroy',
                    'user.active',
                    
                ],
            ],
            //Company
            [
                'group_name' => 'Company',
                'permissions' => [
                    'company.edit',
                    'company.update',
                    
                ],
            ],
            //Agency Genneral (Master Category)
            [
                'group_name' => 'MasterCategory',
                'permissions' => [
                    'general.index',
                    'general.store',
                    'general.show',
                    'general.edit',
                    'general.update',
                    'general.status',
                    'general.destroy',
                    
                ],
            ],
            //Agency Genneral (Partner Setting)
            [
                'group_name' => 'Partner Setting',
                'permissions' => [
                    'patnerSetting.index',
                    'patnerSetting.store',
                    'patnerSetting.status',
                    'patnerSetting.destroy',
                    
                ],
            ],
            //Agency Genneral (Product Type Setting)
            [
                'group_name' => 'Product Type Setting',
                'permissions' => [
                    'productSetting.index',
                    'productSetting.store',
                    'productSetting.status',
                    'productSetting.destroy',
                    
                ],
            ],
            //Agency Genneral (Workflow)
            [
                'group_name' => 'Workflow',
                'permissions' => [
                    'workflow.index',
                    'workflow.store',
                    'workflow.edit',
                    'workflow.update',
                    'workflow.status',
                    
                ],
            ],
            //Agency Genneral (Workflow Document)
            [
                'group_name' => 'Workflow Document',
                'permissions' => [
                    'workflowDocument.index',
                    'workflowDocument.store',
                    'workflowDocument.edit',
                    'workflowDocument.update',
                    'workflowDocument.status',
                    
                ],
            ],
            //Agency Genneral (Workflow Document check list)
            [
                'group_name' => 'Document Check List',
                'permissions' => [
                    'DocumentCheck.index',
                    'DocumentCheck.store',
                    
                ],
            ],
            //Partner Branch
            [
                'group_name' => 'Partner Branch',
                'permissions' => [
                    'partnerBranch.index',
                    'partnerBranch.store',
                    'partnerBranch.show',
                    'partnerBranch.edit',
                    'partnerBranch.update',
                    'partnerBranch.status',
                    'partnerBranch.destroy',
                    
                ],
            ],
            //Academic Setting
            [
                'group_name' => 'Academic',
                'permissions' => [
                    'Academic.index',
                    'Academic.store',
                    'Academic.show',
                    'Academic.edit',
                    'Academic.update',
                    'Academic.status',
                    'Academic.destroy',
                    
                ],
            ],
            //Student Stage
            [
                'group_name' => 'Student Stage',
                'permissions' => [
                    'StudentStage.index',
                    'StudentStage.store',
                    'StudentStage.show',
                    'StudentStage.edit',
                    'StudentStage.update',
                    'StudentStage.status',
                    'StudentStage.destroy',
                    
                ],
            ],
            //Student Source
            [
                'group_name' => 'Student Source',
                'permissions' => [
                    'StudentSource.index',
                    'StudentSource.store',
                    'StudentSource.show',
                    'StudentSource.edit',
                    'StudentSource.update',
                    'StudentSource.status',
                    'StudentSource.destroy',
                    
                ],
            ],
            //Fees
            [
                'group_name' => 'Student Fees',
                'permissions' => [
                    'Fees.index',
                    'Fees.store',
                    'Fees.show',
                    'Fees.edit',
                    'Fees.update',
                    'Fees.status',
                    'Fees.destroy',
                    
                ],
            ],
            //Installment
            [
                'group_name' => 'Installment',
                'permissions' => [
                    'Installment.index',
                    'Installment.store',
                    'Installment.show',
                    'Installment.edit',
                    'Installment.update',
                    'Installment.status',
                    'Installment.destroy',
                    
                ],
            ],
            //Trancaction
            [
                'group_name' => 'Trancaction',
                'permissions' => [
                    'Trancaction.index',
                    'Trancaction.store',
                    'Trancaction.show',
                    'Trancaction.edit',
                    'Trancaction.update',
                    'Trancaction.status',
                    'Trancaction.destroy',
                    
                ],
            ],
            //Trancaction No
            [
                'group_name' => 'TrancactionNo',
                'permissions' => [
                    'TrancactionNo.index',
                    'TrancactionNo.store',
                    'TrancactionNo.show',
                    'TrancactionNo.edit',
                    'TrancactionNo.update',
                    'TrancactionNo.status',
                    'TrancactionNo.destroy',
                    
                ],
            ],
            //branch            
            [
                'group_name' => 'Branch',
                'permissions' => [
                    'branch.index',
                    'branch.store',
                    'branch.show',
                    'branch.edit',
                    'branch.update',
                    'branch.destroy'
                    
                ],
            ],
            //Department            
            [
                'group_name' => 'Department',
                'permissions' => [
                    'department.index',
                    'department.store',
                    'department.show',
                    'department.edit',
                    'department.update',
                    'department.destroy',
                    
                ],
            ],
            //Designation            
            [
                'group_name' => 'Designation',
                'permissions' => [
                    'designation.index',
                    'designation.create',
                    'designation.store',
                    'designation.show',
                    'designation.edit',
                    'designation.update',
                    'designation.destroy'
                    
                ],
            ],
            //Leave Plan            
            [
                'group_name' => 'LeavePlan',
                'permissions' => [
                    'leaveplan.index',
                    'leaveplan.store',
                    'leaveplan.show',
                    'leaveplan.edit',
                    'leaveplan.update',
                    'leaveplan.destroy'
                    
                ],
            ],
             //Working Hour Setup       
            [
                'group_name' => 'Working Hour',
                'permissions' => [
                    'worksetup.index',
                    'worksetup.store',
                    'worksetup.show',
                    'worksetup.edit',
                    'worksetup.update',
                    'worksetup.destroy',
                    'worksetup.status'
                    
                ],
            ],
            //Attendance Setting         
            [
                'group_name' => 'AttendanceSetting',
                'permissions' => [
                    'attendanmst.index',
                    'attendanmst.store',
                    'attendanmst.show',
                    'attendanmst.edit',
                    'attendanmst.update',
                    'attendanmst.destroy'
                    
                ],
            ],
            //Attendance Deduct         
            [
                'group_name' => 'AttendanceDeduct',
                'permissions' => [
                    'deduct.index',
                    'deduct.store',
                    'deduct.show',
                    'deduct.edit',
                    'deduct.update',
                    'deduct.destroy'
                    
                ],
            ],
            //Attendance Status         
            [
                'group_name' => 'Attendance Status',
                'permissions' => [
                    'attendStatus.index',
                    'attendStatus.store',
                    'attendStatus.show',
                    'attendStatus.edit',
                    'attendStatus.update',
                    'attendStatus.destroy'
                    
                ],
            ],
             //Salary Type Setup       
            [
                'group_name' => 'Salary Type Setup',
                'permissions' => [
                    'salaryType.index',
                    'salaryType.store',
                    'salaryType.show',
                    'salaryType.edit',
                    'salaryType.update',
                    'salaryType.destroy',
                    'salaryType.status'
                ],
            ],
            //Holiday Header          
            [
                'group_name' => 'HolidayHeader',
                'permissions' => [
                    'holiday.index',
                    'holiday.store',
                    'holiday.show',
                    'holiday.edit',
                    'holiday.update',
                    'holiday.destroy',
                    'holiday.status',
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
           
            //Personal Information        
            [
                'group_name' => 'PersonalInformation',
                'permissions' => [
                    'personal.index',
                    'personal.store',
                    'personal.show',
                    'personal.edit',
                    'personal.update',
                    'personal.destroy',
                    'personal.status',
                    
                ],
            ],
            //Leave Request      
            [
                'group_name' => 'Leave Request',
                'permissions' => [
                    'Leave.index',
                    'Leave.store',
                    'Leave.show',
                    'Leave.edit',
                    'Leave.update',
                    'Leave.destroy',
                    'Leave.approve',
                    'Leave.reports'
                ],
            ],
            
            //Student 
            [
                'group_name' => 'Student',
                'permissions' => [
                    'Student.index',
                    'Student.store',
                    'Student.create'
                    
                ],
            ],

            //Student Lead
            [
                'group_name' => 'Student Lead',
                'permissions' => [
                    'StudentLead.index',
                    'StudentLead.import',
                    'StudentLead.download'
                    
                ],
            ],

            //Student Lead Reports
            [
                'group_name' => 'Lead Reports',
                'permissions' => [
                    'leadReports.monthly-lead-info',
                    'leadReports.student-transaction',
                    'leadReports.student-revenue',
                ],
            ],

            //Student Application
            [
                'group_name' => 'Student Application',
                'permissions' => [
                    'Application.index',
                    'Application.store',
                    'Application.view',
                    'Application.edit',
                    'Application.update',
                    'Application.destroy',
                    
                ],
            ],

            //Student Service
            [
                'group_name' => 'Student Service',
                'permissions' => [
                    'Service.index',
                    'Service.store',
                    'Service.create',
                    'Service.view',
                    'Service.edit',
                    'Service.update',
                    'Service.destroy',
                    
                ],
            ],
            //Student Document
            [
                'group_name' => 'Student Document',
                'permissions' => [
                    'Document.index',
                    'Document.store',
                    'Document.create',
                    'Document.view',
                    'Document.edit',
                    'Document.update',
                    'Document.destroy',
                    
                ],
            ],
            //Student Quoatations
            [
                'group_name' => 'Student Quoatations',
                'permissions' => [
                    'StudQuoat.index',
                    'StudQuoat.store',
                    'StudQuoat.confirm',
                    'StudQuoat.approval',
                    'StudQuoat.destroy',
                    'StudQuoat.ApprovedReport',
                    'StudQuoat.report',
                    
                ],
            ],
            //Student Accounts
            [
                'group_name' => 'Student Accounts',
                'permissions' => [
                    'StudIns.index',
                    'StudIns.refund',
                    'StudIns.transfer',
                    'StudIns.create',
                    'StudIns.store',
                    'StudIns.view',
                    'StudIns.confirm',
                    'StudIns.approval',
                    'StudIns.destroy',
                    'StudIns.report',
                    
                ],
            ],
            //Partner 
            [
                'group_name' => 'Partner',
                'permissions' => [
                    'Partner.index',
                    'Partner.store',
                    'Partner.create',
                    'Partner.edit',
                    'Partner.update',
                    'Partner.status',
                    'Partner.destroy'
                    
                ],
            ],

            //Product 
            [
                'group_name' => 'Product',
                'permissions' => [
                    'Product.index',
                    'Product.store',
                    'Product.status',
                    'Product.edit',
                    'Product.update',
                    'Product.destroy',
                ],
            ],
            
            //Product Activities
            [
                'group_name' => 'Product Activities',
                'permissions' => [
                    'ProductActivities.aplication',
                    'ProductActivities.fees',
                    'ProductActivities.storefess',
                    'ProductActivities.requirement',
                    'ProductActivities.editRequirement',
                    'ProductActivities.storeRequirement',
                    'ProductActivities.englishTest',
                    'ProductActivities.StoreenglishTest',
                    'ProductActivities.OthersTest',
                    'ProductActivities.StoreOthersTest',
                    'ProductActivities.Promotions',
                ],
            ],
            //Account Setting
            [
                'group_name' => 'Accounts Setting',
                'permissions' => [
                    'accsetting.GroupOne',
                    'accsetting.GroupTwo',
                    'accsetting.GroupThree',
                ],
            ],
            //Group One Setting
            [
                'group_name' => 'Group One',
                'permissions' => [
                    'GroupOne.store',
                    'GroupOne.show',
                    'GroupOne.edit',
                    'GroupOne.update',
                    'GroupOne.status',
                    'GroupOne.destroy',
                ],
            ],
            //Group Two Setting
            [
                'group_name' => 'Group Two',
                'permissions' => [
                    'GroupTwo.store',
                    'GroupTwo.show',
                    'GroupTwo.edit',
                    'GroupTwo.update',
                    'GroupTwo.status',
                    'GroupTwo.destroy',
                ],
            ],
            //Group Three Setting
            [
                'group_name' => 'Group Three',
                'permissions' => [
                    'GroupThree.store',
                    'GroupThree.show',
                    'GroupThree.edit',
                    'GroupThree.update',
                    'GroupThree.status',
                    'GroupThree.destroy',
                ],
            ],
            //Chart Of Accounts
            [
                'group_name' => 'Chart Of Account',
                'permissions' => [
                    'ChartOfAccount.index',
                    'ChartOfAccount.store',
                    'ChartOfAccount.show',
                    'ChartOfAccount.edit',
                    'ChartOfAccount.update',
                    'ChartOfAccount.status',
                    'ChartOfAccount.destroy',
                ],
            ],
            //AC To GL Setup
            [
                'group_name' => 'ACToGL Setup',
                'permissions' => [
                    'ACToGL.index',
                    'ACToGL.store',
                    'ACToGL.show',
                    'ACToGL.edit',
                    'ACToGL.update',
                    'ACToGL.status',
                    'ACToGL.destroy',
                    
                ],
            ],
            //Supplier
            [
                'group_name' => 'Supplier',
                'permissions' => [
                    'Supplier.index',
                    'Supplier.store',
                    'Supplier.show',
                    'Supplier.edit',
                    'Supplier.update',
                    'Supplier.status',
                    'Supplier.destroy',
                ],
            ],
            //Supplier Invocie
            [
                'group_name' => 'Supplier Invoice',
                'permissions' => [
                    'SupplierInvoice.index',
                    'SupplierInvoice.store',
                    'SupplierInvoice.show',
                    'SupplierInvoice.edit',
                    'SupplierInvoice.update',
                    'SupplierInvoice.status',
                    'SupplierInvoice.destroy',
                    'SupplierInvoice.Confirm',
                ],
            ],
             //Supplier Payable
            [
                'group_name' => 'Supplier Payable',
                'permissions' => [
                    'SupplierPayable.index',
                    'SupplierPayable.store',
                    'SupplierPayable.show',
                    'SupplierPayable.edit',
                    'SupplierPayable.update',
                    'SupplierPayable.status',
                    'SupplierPayable.destroy',
                    'SupplierPayable.Confirm',
                ],
            ],
             //Supplier Payment
            [
                'group_name' => 'Supplier Payment',
                'permissions' => [
                    'SupplierPayment.index',
                    'SupplierPayment.store',
                    'SupplierPayment.show',
                    'SupplierPayment.edit',
                    'SupplierPayment.update',
                    'SupplierPayment.status',
                    'SupplierPayment.destroy',
                    'SupplierPayment.Confirm',
                ],
            ],
             //All Voucher
            [
                'group_name' => 'All Voucher List',
                'permissions' => [
                    'voucher.allvoucher',
                    'voucher.allvoucherUpdate',
                    'voucher.allvoucherConfirm',
                    'voucher.allvoucherBalance',
                ],
            ],
             //Opening Balance
            [
                'group_name' => 'Opening Balance',
                'permissions' => [
                    'voucher.opening',
                    'voucher.openingCreate',
                    'voucher.openingUpdate',
                    'voucher.openingConfirm',
                ],
            ],
             //Jurnal Header
            [
                'group_name' => 'Jurnal Voucher',
                'permissions' => [
                    'voucher.jurnal',
                    'voucher.jurnalCreate',
                    'voucher.jurnalUpdate',
                    'voucher.jurnalConfirm',
                ],
            ],
             //Payment Header
            [
                'group_name' => 'Payment Voucher',
                'permissions' => [
                    'voucher.payment',
                    'voucher.paymentCreate',
                    'voucher.paymentUpdate',
                    'voucher.paymentConfirm',
                ],
            ],
             //Receipt Header
            [
                'group_name' => 'Receipt Voucher',
                'permissions' => [
                    'voucher.receipt',
                    'voucher.receiptCreate',
                    'voucher.receiptUpdate',
                    'voucher.receiptConfirm',
                ],
            ],
             //Reverse Header
            [
                'group_name' => 'Reverse Voucher',
                'permissions' => [
                    'voucher.reverse',
                    'voucher.reverseCreate',
                    'voucher.reverseUpdate',
                    'voucher.reverseConfirm',
                ],
            ],
            //Account Money Receipt
            [
                'group_name' => 'Money Receipt',
                'permissions' => [
                    'Accounts.MRIndex',
                    'Accounts.CreateMR',
                    'Accounts.storeMR',
                    'Accounts.ViewMR',
                    'Accounts.CancelMR',
                    'Accounts.ConfirmMR',
                    'Accounts.ReportMR',
                ],
            ],
            //HR Reports
            [
                'group_name' => 'HR Reports',
                'permissions' => [
                    'hrReports.personal-info',
                    'hrReports.personal-info-reports',
                    'hrReports.employee-attendance',
                    'hrReports.employee-attendance-reports',
                    'hrReports.daily-attendance',
                    'hrReports.daily-attendance-reports',
                    'hrReports.monthly-attendance',
                    'hrReports.monthly-attendance-reports',
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
