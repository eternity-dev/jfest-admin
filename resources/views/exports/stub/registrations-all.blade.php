<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Order ID</th>
            <th>Competition Name</th>
            <th>Email</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Instagram</th>
            <th>Nickname</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registrations as $key => $registration)
            <tr>
                <td
                    @if ($registration->competition->use_multi_participant)
                        rowspan="{{ $registration->team->number_of_members + 1 }}"
                    @endif>{{ $key + 1 }}</td>
                <td
                    @if ($registration->competition->use_multi_participant)
                        rowspan="{{ $registration->team->number_of_members + 1 }}"
                    @endif>{{ $registration->order->reference }}</td>
                <td
                    @if ($registration->competition->use_multi_participant)
                        rowspan="{{ $registration->team->number_of_members + 1 }}"
                    @endif>{{ $registration->competition->name }}</td>
                <td>{{ $registration->email }}</td>
                <td>{{ $registration->name }}</td>
                <td>{{ $registration->phone }}</td>
                <td>{{ $registration->instagram ?? '-' }}</td>
                <td>{{ $registration->nickname ?? '-' }}</td>
                <td>
                    @if ($registration->competition->use_multi_participant)
                        Leader
                    @else
                        Solo
                    @endif
                </td>
            </tr>
            @if ($registration->competition->use_multi_participant)
                @foreach ($registration->team->members as $member)
                    <tr>
                        <td>-</td>
                        <td>{{ $member->name }}</td>
                        <td>-</td>
                        <td>{{ $member->instagram ?? '-' }}</td>
                        <td>{{ $member->nickname ?? '-' }}</td>
                        <td>Member</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
