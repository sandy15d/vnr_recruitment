<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_mrf extends Model
{
    use HasFactory;

    protected $table = 'manpowerrequisition';
    protected $primaryKey = 'MRFId';
    public $timestamps = false;
    protected $fillable = [
        'MRFId',
        'JobCode',
        'PositionCode',
        'Type',
        'RepEmployeeID',
        'Reason',
        'CompanyId',
        'DepartmentId',
        'DesigId',
        'GradeId',
        'Positions',
        'LocationIds',
        'Reporting',
        'ExistCTC',
        'MinCTC',
        'MaxCTC',
        'WorkExp',
        'Remarks',
        'EducationId',
        'EducationInsId',
        'KeyPositionCriteria',
        'KPC',
        'Status',
        'RemarkHr',
        'Allocated',
        'AllocatedDt',
        'CloseDt',
        'OnBehalf',
        'CreatedTime',
        'CreatedBy',
        'LastUpdated',
        'UpdatedBy',
        'reporting_id',
        'hod_id', 'management_id'
    ];
}
