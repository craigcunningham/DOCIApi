<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return getmypid();
});

//Owners Routing
$router->get('DociOwners', function() {
    $owners = App\Models\DociOwner::all();
    return $owners;
});

$router->get('DociOwners/{id}', function($id) {
    $owner = App\Models\DociOwner::find($id);
    return $owner;
});

$router->post('DociOwners', function(\Illuminate\Http\Request $request) {
    $owner = App\Models\DociOwner::create();
    $owner->name = $request->json()->get('name');
    $owner->email = $request->json()->get('email');
    $owner->save();
    return($owner);
});

$router->put('DociOwners', function(\Illuminate\Http\Request $request) {
    $owner = App\Models\DociOwner::find($request->json()->get('id'));
    $owner->name = $request->json()->get('name');
    $owner->email = $request->json()->get('email');
    $owner->save();
    return($owner);
});

$router->delete('DociOwners/{id}', function($id) {
    App\Models\DociOwner::destroy($id);
});

//Teams Routing
$router->get('DociTeams', function() {
    $teams = App\Models\DociTeam::all();
    return $teams;
});

$router->get('DociTeams/BySeason/{seasonid}', function($seasonid) {
    $teams = DB::select("SELECT COUNT(*), docilineup.team_id AS id, dociteam.name AS name
                    FROM docilineup 
                    JOIN dociteam ON docilineup.team_id = dociteam.id
                    WHERE docilineup.season_id = :seasonid
                    GROUP BY id, name", 
                    ['seasonid' => $seasonid]);
     return $teams;
});

$router->get('DociTeams/{id}', function($id) {
    $team = App\Models\DociTeam::find($id)::with('owner');
    //Log::info('Get DociTeam owner: ' . $team->owner_id);
    return $team;
});

$router->post('DociTeams', function(\Illuminate\Http\Request $request) {
    $team = App\Models\DociTeam::create();
    $team->name = $request->json()->get('name');
    $team->owner = $request->json()->get('owner')->get('id');
    $team->save();
    return($team);
});

$router->put('DociTeams', function(\Illuminate\Http\Request $request) {
    $team = App\Models\DociTeam::find($request->json()->get('id'));
    $team->name = $request->json()->get('name');
    $team->owner = $request->json()->get('owner')->get('id');
    $team->save();
    return($team);
});

$router->delete('DociTeams/{id}', function($id) {
    //App\Models\DociTeam::destroy($id);
});

//Seasons Routing
$router->get('DociSeasons', function() {
    $seasons = App\Models\DociSeason::all();
    return $seasons;
});

$router->get('DociSeasons/GetCurrentSeason', function() {
    $year = Date("Y");
    $season = App\Models\DociSeason::where('year', '=', $year)->get();
    return $season;
});

$router->get('DociSeasons/{id}', function($id) {
    $seasons = DB::select("SELECT id, DATE_FORMAT(start_date, '%m/%d/%Y') AS initialDate, 
                    DATE_FORMAT(supplementalDate, '%m/%d/%Y') AS supplementalDate, name
                    FROM dociseason 
                    WHERE id = :id", ["id" => $id]);
    return $seasons;
});

$router->post('DociSeasons', function(\Illuminate\Http\Request $request) {
    $season = App\Models\DociSeason::create();
    $season->name = $request->json()->get('name');
    $season->start_date = $request->json()->get('initialDate');
    $season->supplementalDate = $request->json()->get('supplementalDate');
    $season->save();
    return($season);
});

$router->put('DociSeasons', function(\Illuminate\Http\Request $request) {
    $season = App\Models\DociSeason::find($request->json()->get('id'));
    $season->name = $request->json()->get('name');
    $season->owner = $request->json()->get('owner')->get('id');
    $season->save();
    return($season);
});

$router->delete('DociSeasons/{id}', function($id) {
    //App\Models\DociSeason::destroy($id);
});

//Rosters Routing
$router->get('Rosters', function() {
    $seasons = App\Models\DociRoster::all();
    return $seasons;
});

$router->get('Rosters/{id}', function($id) {
    $roster = App\Models\DociRoster::find($id);
    return $season;
});

$router->get('Rosters/BySeason/{seasonid}', function($seasonid) {
    $rosters = DB::select("SELECT docilineup.id, docilineup.team_id, docilineup.player_id,
                    docilineup.position, docilineup.season_id, docilineup.date_added,
                    dociteam.name AS team_name, 
                    daflplayer.fullName AS player_name
                    FROM docilineup 
                    JOIN dociteam ON docilineup.team_id = dociteam.id
                    JOIN daflplayer ON docilineup.player_id = daflplayer.DAFLID
                    WHERE docilineup.season_id = :seasonid", ['seasonid' => $seasonid]);
    return $rosters;
});

$router->get('Rosters/ByTeamAndSeason/{teamid}/{seasonid}', function($teamid, $seasonid) {
    $roster = DB::table('docilineup')->where([['team_id', $teamid], ['season_id', $seasonid]])->get();
    return $roster;
});

$router->post('Rosters', function(\Illuminate\Http\Request $request) {
    $roster = App\Models\DociRoster::create();
    $roster->player_id = $request->json()->get('player_id');
    $roster->team_id = $request->json()->get('team_id');
    $roster->season_id = $request->json()->get('season_id');
    $roster->position = $request->json()->get('position');
    $roster->save();
    $rosters = DB::select("SELECT docilineup.id, docilineup.team_id, docilineup.player_id,
                    docilineup.position, docilineup.season_id, docilineup.date_added,
                    dociteam.name AS team_name, 
                    daflplayer.fullName AS player_name
                    FROM docilineup 
                    JOIN dociteam ON docilineup.team_id = dociteam.id
                    JOIN daflplayer ON docilineup.player_id = daflplayer.DAFLID
                    WHERE docilineup.id = :rosterid", ['rosterid' => $roster->id]);
    return($rosters);
});

$router->put('Rosters', function(\Illuminate\Http\Request $request) {
    $roster = App\Models\DociRoster::find($request->json()->get('id'));
    $roster->save();
    return($roster);
});

$router->delete('Rosters/{id}', function($id) {
    App\Models\DociRoster::destroy($id);
});

// Player routes
$router->get('Players/SearchByName/{searchTerm}', function($searchTerm) {
    $playerName = urldecode($searchTerm);
    $fullName = '%' . $playerName . '%';

    $players = DB::select("SELECT DAFLID as id, 
    daflplayer.fullName AS name,
    CASE daflplayer.pitcher_ind WHEN 1 THEN 'P' WHEN 0 THEN 'H' ELSE 'H' END AS position,
    daflplayer.teamCode AS mlbteam
    FROM  daflplayer
    WHERE fullName like :fullName", ['fullName' => $fullName]);
    return $players;
/*
    $lastName = '%';
    $firstName = '%';
        if(strrchr($playerName, ','))
    {
        $names = explode(",", $playerName);
        $lastName = trim($names[0]) . "%";
        $firstName = trim($names[1]) . "%";
    }
    elseif(strrchr($playerName, ' '))
    {
        $names = explode(" ", $playerName);
        $firstName = trim($names[0]) . "%";
        $lastName = trim($names[1]) . "%";
}
    else
    {
        $firstName = strtolower($playerName) . "%";
        $lastName = strtolower($playerName) . "%";
    }

    if($firstName == $lastName)
    {
        $players = DB::select("SELECT DAFLID as id, 
                    CONCAT(daflplayer.firstName, ' ', daflplayer.lastName) AS name,
                    CASE daflplayer.pitcher_ind WHEN 1 THEN 'P' WHEN 0 THEN 'H' ELSE 'H' END AS position
                    FROM  daflplayer
                    WHERE firstName like :firstName or lastName like :lastName", ['lastName' => $lastName, 'firstName' => $firstName]);
    }
    else
    {
        $players = DB::select("SELECT DAFLID as id, 
                    CONCAT(daflplayer.firstName, ' ', daflplayer.lastName) AS name,
                    CASE daflplayer.pitcher_ind 
                    WHEN 1 THEN 'P' 
                    WHEN 0 THEN 'H' 
                    ELSE 'H' 
                    END AS position
                    FROM  daflplayer
                    WHERE firstName like :firstName and lastName like :lastName", ['lastName' => $lastName, 'firstName' => $firstName]);
    }
    return $players;
*/
});
