@php
    use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// Fetch MRF details
$basicDetails = \App\Models\master_mrf::find($MRFId);

// Define the function within the Blade file
function processLocationDetails($locationIds)
{
    if (empty($locationIds)) {
        return '';
    }

    $locations = unserialize($locationIds);
    $locArray = [];

    foreach ($locations as $value) {
        $city = !empty($value['city']) ? getDistrictName($value['city']) : '';
        $state = getStateCode($value['state']);
        $nop = $value['nop'] ?? '';

        $locArray[] = "{$city} ({$state}) - {$nop}";
    }

    // Convert location array to string with line breaks
    return implode(',&nbsp;&nbsp;', $locArray);
}

// Call the function to process location details
$locationDetails = processLocationDetails($basicDetails->LocationIds);

$totalApplication = \App\Models\master_mrf::join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')
->join('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')
->where('manpowerrequisition.MRFId', $MRFId)
->count('jobapply.JAId');



// Fetch the start date from the 'manpowerrequisition' table where 'MRFId' is $MRFId
$startDate = Carbon::parse(DB::table('manpowerrequisition')->where('MRFId', $MRFId)->value('CreatedTime'));

// Get the current date
$endDate = Carbon::now();

// Initialize an empty array to store results
$data = [];

// Loop through each date between startDate and endDate
for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
// Format the current date in the loop
$currentDate = $date->format('Y-m-d');

// Count HR screenings performed on the current date where the 'Status' is not null
$totalScreenings = DB::table('manpowerrequisition')
    ->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')
    ->join('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')
    ->where('manpowerrequisition.MRFId', $MRFId)
    ->whereDate('jobapply.HrScreeningDate', $currentDate)
    ->whereNotNull('jobapply.Status')
    ->count('jobapply.JAId');

// Calculate the date difference between the current date and the MRF creation date

 // Set date difference to zero if totalScreenings is zero
    $dateDifference = $totalScreenings > 0 ? $date->diffInDays($startDate) : 0;

// Count HR Fwd to Technical Screening on the current date where `FwdTechScr` is Yes
$totalFwdScr = DB::table('manpowerrequisition')
    ->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')
    ->join('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')
    ->join('screening', 'screening.JAId', '=', 'jobapply.JAId')
    ->where('manpowerrequisition.MRFId', $MRFId)
    ->where('jobapply.FwdTechScr', 'Yes') // Corrected to check value instead of using whereDate
    ->whereDate('screening.ReSentForScreen', $currentDate)
    ->whereNotNull('jobapply.Status')
    ->distinct()
    ->count('jobapply.JAId');
 // Set date difference to zero if totalScreenings is zero
    $dateDifferenceFwdScr = $totalFwdScr > 0 ? $date->diffInDays($startDate) : 0;

// Append the result to the array
$data[] = [
    'Date' => $currentDate,
    'HR_Screening' => $totalScreenings,
    'DateDifferenceHRScreening' => $dateDifference,
    'Fwd_Scr'=>$totalFwdScr,
    'DateDifferenceFwdScr'=>$dateDifferenceFwdScr,
];
}



@endphp

{{-- Display the table --}}
<table>
    <tr></tr>
    <tr>
        <td>MRF:</td>
        <td colspan="4">{{ $basicDetails->JobCode }}</td>
        <td></td>
        <td>Department:</td>
        <td colspan="4">{{ getDepartment($basicDetails->DepartmentId) }}</td>
        <td></td>
        <td>MRF Date:</td>
        <td colspan="4">{{ \Carbon\Carbon::parse($basicDetails->CreatedTime)->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td>Location:</td>
        <td colspan="10">{!! $locationDetails !!}</td>
        <td></td>
        <td>Total Applicants</td>
        <td colspan="2">{{$totalApplication}}</td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <th>Date</th>
        <th>HR Screening</th>
        <th>No of Days from MRF</th>
        <th></th>
        <th>Fwd to Technical Screening</th>
        <th>No of Days from MRF</th>
    </tr>
    @foreach($data as $row)
        <tr>
            <td>{{$row['Date']}}</td>
            <td>{{$row['HR_Screening']}}</td>
            <td>{{$row['DateDifferenceHRScreening']}}</td>
            <td></td>
            <td>{{$row['Fwd_Scr']}}</td>
            <td>{{$row['DateDifferenceFwdScr']}}</td>
        </tr>
    @endforeach
</table>
