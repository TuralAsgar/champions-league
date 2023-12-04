<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Premier League Simulation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <script src="{{asset('/js/script.js')}}"></script>
</head>
<body>
<div class="container">

    @if($standing)
        <div class="row standings-box">
            <div class="col-md-12">
                <h2 class="standings-box_title">Standings</h2>
                <div class="table-responsive">
                    <table id="standings-table" class="table table-bordred table-striped">
                        <thead>
                        <th>Teams</th>
                        <th>P</th>
                        <th>W</th>
                        <th>D</th>
                        <th>L</th>
                        <th>GD</th>
                        <th>PTS</th>
                        </thead>
                        <tbody>
                        @foreach($standing as $team)
                            <tr>
                                <td>
                                    {{$team->name}}
                                </td>
                                <td>{{$team->played}}</td>
                                <td>{{$team->won}}</td>
                                <td>{{$team->draw}}</td>
                                <td>{{$team->lose}}</td>
                                <td>{{$team->goal_difference}}</td>
                                <td>{{$team->points}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    @endif

    <div class="row fixtures-box">
        <div class="col-md-8">
            <table class="table table-hover">
                <thead>
                <tr>
                    <td class="make-center" colspan="3">
                        <h3>
                            Future Fixtures
                        </h3>
                    </td>
                </tr>
                </thead>
                <tbody id="table-body">
                @if (!empty($weeks))
                    @foreach($weeks as $week)
                        <tr>
                            <td colspan="3" class="text-center font-weight-bold bg-warning">
                                {{$week->title}} Week Matches
                            </td>
                        </tr>
                        <tr class="fixtures-box_header">
                            <td>Home</td>
                            <td>Goals</td>
                            <td>Away</td>
                        </tr>
                        @if($games)
                            @foreach ($games[$week->id] as $fixture)
                                <tr>
                                    <td class="make-center">
                                        {{$fixture['home_team']}}
                                    </td>
                                    <td class="make-center">{{$fixture['home_team_goal']}}
                                        - {{$fixture['away_team_goal']}}</td>
                                    <td class="make-center">
                                        {{$fixture['away_team']}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            @if($fixture['status'] == 0)
                                <td colspan="5" class="make-center weekly-simulate-button">
                                    <button data-week="{{$week->id}}" class="btn btn-success play-week">
                                        Simulate {{$week->title}} Week
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="make-center make-full-width">
                    <button class="btn btn-success simulate-all-weeks">
                        Simulate All Weeks
                    </button>
                </div>
                <div class="make-full-width make-center">
                    <button class="btn btn-danger reset-all">Reset All</button>
                </div>
            </div>
            <div class="row prediction-wrapper">
                <h3 class="prediction-box_title">Prediction</h3>
                <table class='table table-dark make-full-width'>
                    <thead>
                    <tr>
                        <th scope='col'>Team</th>
                        <th scope='col'>Percentage</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($predictions)
                        @foreach($predictions as $team => $percent)
                            <tr>
                                <th scope='row'> {{ $team  }} </th>
                                <td> {{ $percent }} %</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
