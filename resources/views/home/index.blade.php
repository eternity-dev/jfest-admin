@extends("template.app")
@section("content")
    <div class="container py-4" style="width: 100%">
        <header class="d-flex align-items-end" style="width: 100%">
            <div>
                <h3 class="mb-1">Hello, {{ $user->name }}!</h3>
                <span class="text-muted">
                    Here your dashboard and event analytics data.
                </span>
            </div>
            <div class="d-flex gap-3 ms-auto">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">
                        <i class="ri-file-excel-line pe-1"></i>
                        <span>Export to Excel</span>
                    </button>
                </div>
            </div>
        </header>
        <main class="d-flex flex-column gap-4 py-4">
            <section class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted">Balance</span>
                                <h3 class="m-0 mt-1">Rp {{ $total_balance }}</h3>
                            </div>
                            <div style="font-size: 40px">
                                <i class="ri-money-dollar-circle-line text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted">Paid Tickets</span>
                                <h3 class="m-0 mt-1">{{ $total_paid_tickets }}</h3>
                            </div>
                            <div style="font-size: 40px">
                                <i class="ri-coupon-line text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted">Free Pass Tickets</span>
                                <h3 class="m-0 mt-1">{{ $total_free_pass_tickets }}</h3>
                            </div>
                            <div style="font-size: 40px">
                                <i class="ri-coupon-line text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted">Total Registrations</span>
                                <h3 class="m-0 mt-1">{{ $total_registrations }}</h3>
                            </div>
                            <div style="font-size: 40px">
                                <i class="ri-draft-line text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="row gap-1">
                <div class="col">
                    <section class="card" style="height: fit-content">
                        <div class="card-body d-flex flex-column gap-2" style="height: fit-content">
                            <span class="text-muted">Max Profits Per Hour</span>
                            <canvas
                                id="transaction-chart"
                                style="width: 100%; height: 300px"
                                data-payments="{{ $json_paid_payments }}"
                                data-current-date="{{ now() }}"></canvas>
                        </div>
                    </section>
                </div>
                <div class="col">
                    <section class="card" style="height: fit-content">
                        <div class="card-body d-flex flex-column gap-2" style="height: fit-content">
                            <span class="text-muted">Tickets Sold Per Hour</span>
                            <canvas
                                id="ticket-chart"
                                style="width: 100%; height: 300px"
                                data-tickets="{{ $json_paid_tickets }}"
                                data-current-date="{{ now() }}"></canvas>
                        </div>
                    </section>
                </div>
            </div>
            <div class="row gap-1">
                <div class="col">
                    <div class="card">
                        <div class="card-body d-flex flex-column gap-2" style="height: fit-content">
                            <span class="text-muted">Used Methods</span>
                            <canvas
                                id="used-method-chart"
                                style="width: 100%"
                                data-methods="{{ $json_payment_methods }}"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-body d-flex flex-column gap-2" style="height: fit-content">
                            <span class="text-muted">Sum of Profits</span>
                            <canvas
                                id="sum-of-transaction-chart"
                                style="width: 100%; height: 300px"
                                data-sum-of-payments="{{ $json_sum_of_paid_payments }}"
                                data-current-month="{{ now()->englishMonth }}"
                                data-start-date="{{ now()->subDays(5) }}"
                                data-end-date="{{ now()->addDays(1) }}"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card">
                        <div class="card-body d-flex flex-column gap-2" style="height: fit-content">
                            <span class="text-muted">Count of Registrations</span>
                            <canvas
                                id="reg-count-chart"
                                style="width: 100%"
                                data-regs="{{ $json_registrations_count_by_comp }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
@section("scripts")
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"
        integrity="sha512-SIMGYRUjwY8+gKg7nn9EItdD8LCADSDfJNutF9TPrvEo86sQmFMh6MyralfIyhADlajSxqc7G0gs7+MwWF/ogQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script type="module" defer>
        function* dateRangeFromHour(start) {
            const current = new Date(start);
            current.setHours(0, 0, 0, 0);

            for (let hour = 0; hour < 24; hour++) {
                const currentHour = new Date(current);
                currentHour.setHours(hour, 0, 0, 0);
                yield currentHour.getHours();
            }
        }

        function* dateRangeFrom(start, end, step = 1) {
            let d = start;
            while (d < end) {
                yield `${new Date(d).getDate()}/${new Date(d).getMonth()}`;
                d.setDate(d.getDate() + step)
            }
        }

        function formatCurrency(amount) {
            if (amount >= 1000000) return (amount / 1000000).toFixed(1) + 'M';
            else if (amount >= 1000) return (amount / 1000).toFixed(1) + 'k';
            return amount;
        }

        window.onload = function () {
            const trxChartElem = document.querySelector('#transaction-chart');
            const trxChart = new Chart(trxChartElem, {
                type: 'line',
                data: {
                    labels: [...dateRangeFromHour(trxChartElem.dataset.currentDate)],
                    datasets: [{
                        label: 'Per Hour Profits',
                        backgroundColor: '#ff000015',
                        borderColor: '#f00',
                        fill: true,
                        tension: 0.25,
                        data: JSON.parse(trxChartElem.dataset.payments).map((payment) => {
                            return ({
                                x: payment.hour,
                                y: payment.max_amount
                            })
                        })
                    }]
                },
                options: {
                    scales: {
                        y: {
                            min: 0,
                            ticks: { callback: (v) => formatCurrency(v), stepSize: 50000 }
                        }
                    }
                }
            });

            const ticketsChartElem = document.querySelector('#ticket-chart');
            const ticketsChart = new Chart(ticketsChartElem, {
                type: 'line',
                data: {
                    labels: [...dateRangeFromHour(ticketsChartElem.dataset.currentDate)],
                    datasets: [{
                        label: 'Tickets Per Hour',
                        backgroundColor: '#0000ff15',
                        borderColor: '#00f',
                        fill: true,
                        tension: 0.35,
                        data: JSON.parse(ticketsChartElem.dataset.tickets).map((ticket) => {
                            console.log(ticket)
                            return ({
                                x: ticket.hour,
                                y: ticket.total_tickets
                            })
                        })
                    }]
                },
                options: {
                    scales: {
                        y: { min: 0 }
                    }
                }
            })

            const sumOfTrxChartElem = document.querySelector('#sum-of-transaction-chart');
            const sumOfTrxChart = new Chart(sumOfTrxChartElem, {
                type: 'bar',
                data: {
                    labels: [...dateRangeFrom(
                        new Date(sumOfTrxChartElem.dataset.startDate),
                        new Date(sumOfTrxChartElem.dataset.endDate),
                    )],
                    datasets: [{
                        label: 'Paid Amount',
                        backgroundColor: ['#0000ff15', '#ff000015'],
                        borderColor: ['#0000ff', '#ff0000'],
                        borderWidth: 1,
                        data: JSON.parse(sumOfTrxChartElem.dataset.sumOfPayments).map((payment) => {
                            return ({
                                x: `${new Date(payment.date).getDate()}/${new Date(payment.date).getMonth()}`,
                                y: payment.sum_of_amount + payment.sum_of_fee
                            })
                        })
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (v) => formatCurrency(v) } }
                    }
                },
                plugins: {
                    color: { enable: true, forceOverride: true }
                }
            });

            const methodsChartElem = document.querySelector('#used-method-chart');
            const methodsChartData = JSON.parse(methodsChartElem.dataset.methods);
            const methodsChart = new Chart(methodsChartElem, {
                type: 'polarArea',
                data: {
                    labels: methodsChartData.map(method => method.method),
                    datasets: [{
                        backgroundColor: ['#ff000030', '#0000ff30', '#00ff0030'],
                        data: methodsChartData.map(method => method.total_used)
                    }]
                },
                options: {}
            });

            const regsCountChartElem = document.querySelector('#reg-count-chart');
            const regsCountChartData = JSON.parse(regsCountChartElem.dataset.regs);
            const regsCountChart = new Chart(regsCountChartElem, {
                type: 'bar',
                data: {
                    labels: regsCountChartData.map(reg => reg.name),
                    datasets: [{
                        label: 'Participants',
                        backgroundColor: '#ff000050',
                        borderColor: '#ff0000',
                        data: regsCountChartData.map(reg => reg.registrations_count),
                    }]
                },
                options: { indexAxis: 'y', x: { min: 0 } }
            });
        }
    </script>
@endsection
