<div class="py-12" id="elozo-het">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-white text-white view-reports-padding">
                <p class="top5">Előző hét (lezárt)</p>
                <table class="display view-reports" id="closed-weekly-stats">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">IC név</th>
                            <th scope="col">Jelentések</th>
                            <th scope="col">Utolsó jelentés</th>
                            <th scope="col">Jelentések megtekintése</th>
                            <th scope="col">Szolgálati idő (perc)</th>
                            <th scope="col">Utolsó szolgálat leadása</th>
                            <th scope="col">Szolgálatok megtekintése</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($closedUserStats as $closedUserStat)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $closedUserStat->charactername }}</td>
                            <td>{{ $closedUserStat->reportCount }}</td>
                            @if ($closedUserStat->lastReportDate != '-')
                                <td>{{ \Illuminate\Support\Carbon::parse($closedUserStat->lastReportDate)->format('Y.m.d H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.viewClosedUserReports', $closedUserStat->id) }}" method="get" target="_blank_{{ $loop->iteration }}">
                                        <x-primary-button>
                                            {{ __('Jelentések') }}
                                        </x-primary-button>
                                    </form>
                                </td>
                            @else
                                <td>-</td>
                                <td>-</td>
                            @endif
                            <td>{{ $closedUserStat->dutyMinuteSum }}</td>
                            @if ($closedUserStat->lastDutyDate != '-')
                                <td>{{ \Illuminate\Support\Carbon::parse($closedUserStat->lastDutyDate)->format('Y.m.d H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.viewClosedUserDuty', $closedUserStat->id) }}" method="get" target="_blank_{{ $loop->iteration }}">
                                        <x-primary-button>
                                            {{ __('Szolgálatok') }}
                                        </x-primary-button>
                                    </form>
                                </td>
                            @else
                                <td>-</td>
                                <td>-</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>