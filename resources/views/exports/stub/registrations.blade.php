@foreach ($registrations as $registration)
    <table>
        <tr><td colspan="3">Order : {{ $registration->order->reference }}</td></tr>
        <tr><td colspan="3">Competition : {{ $registration->competition->name }}</td></tr>
        @if (!is_null($registration->team))
            <tr><td colspan="3">Team : {{ $registration->team->name }}</td></tr>
        @endif
    </table>
    <table border="2">
        <thead>
            <tr>
                <th align="center">No</th>
                <th align="center">Email</th>
                <th align="center">Name</th>
                <th align="center">Phone</th>
                <th align="center">Instagram</th>
                <th align="center">Nickname</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">1</td>
                <td>{{ $registration->email }}</td>
                <td>{{ $registration->name }}</td>
                <td>{{ $registration->phone }}</td>
                <td>{{ $registration->instagram ?? '-' }}</td>
                <td>{{ $registration->nickname ?? '-' }}</td>
            </tr>
            @if (!is_null($registration->team))
                <tr><td colspan="6" align="center">Team Members</td></tr>
                @foreach ($registration->team->members as $key => $member)
                    <tr>
                        <td align="center">{{ 1 + ($key + 1) }}</td>
                        <td>-</td>
                        <td>{{ $member->name }}</td>
                        <td>-</td>
                        <td>{{ $member->instagram ?? '-' }}</td>
                        <td>{{ $member->nickname ?? '-' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endforeach
