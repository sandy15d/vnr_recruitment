<?php

namespace App\Models\Recruiter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_post extends Model
{
    use HasFactory;
    protected $table = 'jobpost';
    protected $primaryKey = 'JPId';
    public $timestamps = false;
    protected $fillable = [
        'MRFId',
        'CompanyId',
        'GradeId',
        'DepartmentId',
        'DesigId',
        'JobCode',
        'Title',
        'ReqQualification',
        'Description',
        'PayPackage',
        'State',
        'Location',
        'KeyPositionCriteria',
        'PostingView',
        'Status',
        'JobPostType',
        'LastDate',
        'CreatedBy',
        'LastUpdated',
        'UpdatedBy'
    ];
}
