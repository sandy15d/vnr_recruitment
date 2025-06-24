<?php

namespace App\Spotlight;

use App\Models\jobapply;
use App\Models\jobcandidate;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class CandidateDetail extends SpotlightCommand
{

    protected string $name = 'Candidate Detail';


    protected string $description = 'Candidate Profile Detail';

    /*  protected array $synonyms = [
        'Job Application'
    ]; */

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()
            ->add(

                SpotlightCommandDependency::make('candidate')

                    ->setPlaceholder('which candidate?')
            );
    }


    public function searchCandidate($query)
    {
        return jobcandidate::join('jobapply', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->join('screening', 'screening.JAId', '=', 'jobapply.JAId')
            ->where('screening.ScreenStatus', '=', 'Shortlist')
            ->where(function ($subquery) use ($query) {
                $subquery->where('jobcandidates.FName', 'like', "%$query%")
                    ->orWhere('jobcandidates.Email', 'like', "%$query%")
                    ->orWhere('jobcandidates.Phone', 'like', "%$query%");
            })

            ->get()
            ->map(function (jobcandidate $team) {
                return new SpotlightSearchResult(
                    $team->JCId,
                    $team->FName . ' ' . $team->MName . ' ' . $team->LName .'( '.$team->Phone.' , '.$team->Email.' )',
                    sprintf('Show Detail of %s', $team->FName . ' ' . $team->MName . ' ' . $team->LName  ),
                );
            });
    }



    public function execute(Spotlight $spotlight, jobcandidate $candidate)

    {
        $JCId = $candidate->JCId;
        $JAId = jobapply::where('JCId', '=', $JCId)->value('JAId');
        $JAId = base64_encode($JAId);
        $spotlight->redirect('/candidate_detail?jaid=' . $JAId);
    }


    public function shouldBeShown(Request $request): bool
    {
        return auth()->user()->role == 'A' || auth()->user()->role == 'R';
    }
}
