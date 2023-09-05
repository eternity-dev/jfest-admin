import {
    BarController,
    BarElement,
    CategoryScale,
    Chart,
    Colors,
    LineController,
    LinearScale,
    LineElement,
    PointElement,
    RadarController,
    RadialLinearScale,
    TimeScale,
    Tooltip,
} from "chart.js";
import "chartjs-adapter-date-fns";

const toastElemList = document.querySelectorAll(".toast");
const toastList = [...toastElemList].map((el) => {
    new bootstrap.Toast(el, {}).show();
});

const COLORS = {
    primary: "#36A2EB",
    danger: "#FF6384",
    success: "#4BC0C0",
    secondary: "#9966FF",
    warning: "#FFCD56",
};

Chart.register(BarController);
Chart.register(BarElement);
Chart.register(CategoryScale);
Chart.register(Colors);
Chart.register(LinearScale);
Chart.register(LineController);
Chart.register(LineElement);
Chart.register(PointElement);
Chart.register(RadarController);
Chart.register(RadialLinearScale);
Chart.register(TimeScale);
Chart.register(Tooltip);

const ticketsSoldChartElem = document.getElementById("chart-tickets-sold");
const totalRevenueChartElem = document.getElementById("chart-total-revenue");
const paymentsMChartElem = document.getElementById("chart-payments");
const regisChartElem = document.getElementById("chart-registrations");
const usersChartElem = document.getElementById("chart-users");

if (
    [
        ticketsSoldChartElem,
        totalRevenueChartElem,
        paymentsMChartElem,
        regisChartElem,
        usersChartElem,
    ].every((elem) => {
        return document.body.contains(elem);
    })
) {
    const ticketsSoldData = JSON.parse(ticketsSoldChartElem.dataset.tickets);
    const ticketsSoldChart = new Chart(ticketsSoldChartElem, {
        type: "line",
        data: {
            labels: [],
            datasets: [
                {
                    borderColor: COLORS.primary,
                    borderWidth: 2,
                    pointRadius: 0,
                    cubicInterpolationMode: "monotone",
                    data: ticketsSoldData.map((t) => {
                        const [tYear, tMonth, tDay] =
                            t.date_of_created_at.split("-");
                        const tHour = t.hour_of_created_at;
                        const tMin = t.min_of_created_at;

                        const d = new Date();
                        d.setFullYear(+tYear, tMonth - 1, +tDay);
                        d.setHours(tHour, tMin, 0);

                        return { x: d, y: t.total_sold };
                    }),
                },
            ],
        },
        options: {
            interaction: { mode: "index", intersect: false },
            scales: {
                x: {
                    type: "time",
                    time: {
                        unit: "day",
                        isoWeekday: true,
                    },
                },
                y: {
                    type: "linear",
                    display: true,
                    ticks: { stepSize: 1 },
                    suggestedMin: 0,
                    position: "left",
                },
            },
        },
    });

    const totalRevenueData = JSON.parse(totalRevenueChartElem.dataset.revenue);
    const totalRevenueChart = new Chart(totalRevenueChartElem, {
        type: "line",
        data: {
            labels: [],
            datasets: [
                {
                    borderColor: COLORS.success,
                    borderWidth: 2,
                    pointRadius: 0,
                    cubicInterpolationMode: "monotone",
                    data: totalRevenueData.map((r) => {
                        const [rYear, rMonth, rDay] =
                            r.date_of_created_at.split("-");
                        const rHour = r.hour_of_created_at;
                        const rMin = r.min_of_created_at;

                        const d = new Date();
                        d.setFullYear(+rYear, rMonth - 1, +rDay);
                        d.setHours(rHour, rMin, 0);

                        return {
                            x: d,
                            y: r.total_paid_amount + r.total_paid_fee,
                        };
                    }),
                },
            ],
        },
        options: {
            interaction: { mode: "index", intersect: false },
            scales: {
                x: {
                    type: "time",
                    time: {
                        unit: "day",
                        isoWeekday: true,
                    },
                },
                y: {
                    type: "linear",
                    display: true,
                    ticks: {
                        callback(value) {
                            if (value >= 1000000)
                                return `${(value / 1000000).toFixed(1)}M`;
                            if (value >= 1000)
                                return `${(value / 1000).toFixed(1)}k`;

                            return value;
                        },
                        stepSize: 50000,
                    },
                    suggestedMin: 0,
                    position: "left",
                },
            },
        },
    });

    const paymentsMethodChartData = JSON.parse(
        paymentsMChartElem.dataset.payments
    );
    const paymentsMethodChart = new Chart(paymentsMChartElem, {
        type: "bar",
        data: {
            labels: paymentsMethodChartData.map((pM) => pM.method),
            datasets: [
                {
                    data: paymentsMethodChartData.map((pM) => pM.total_used),
                    borderWidth: 1.5,
                    borderColor: [COLORS.primary, COLORS.danger],
                    backgroundColor: [
                        `${COLORS.primary}50`,
                        `${COLORS.danger}50`,
                    ],
                },
            ],
        },
        options: {
            scales: {
                x: {
                    ticks: {
                        callback(_, i) {
                            const curr = paymentsMethodChartData[i].method;
                            return curr[0].toUpperCase() + curr.slice(1);
                        },
                    },
                },
            },
        },
    });

    const regisChartData = JSON.parse(regisChartElem.dataset.registrations);
    const regisChart = new Chart(regisChartElem, {
        type: "bar",
        data: {
            labels: regisChartData.map((rg) => rg.competition.name),
            datasets: [
                {
                    data: regisChartData.map((rg) => rg.total_registered),
                    borderWidth: 1.5,
                    borderColor: [
                        COLORS.danger,
                        COLORS.primary,
                        COLORS.secondary,
                        COLORS.success,
                        COLORS.warning,
                    ],
                    backgroundColor: [
                        `${COLORS.danger}50`,
                        `${COLORS.primary}50`,
                        `${COLORS.secondary}50`,
                        `${COLORS.success}50`,
                        `${COLORS.warning}50`,
                    ],
                },
            ],
        },
        options: {
            indexAxis: "y",
            scales: { y: { position: "left" } },
        },
    });

    const usersChartData = JSON.parse(usersChartElem.dataset.users);
    const usersChart = new Chart(usersChartElem, {
        type: "line",
        data: {
            datasets: [
                {
                    borderColor: COLORS.secondary,
                    borderWidth: 2,
                    pointRadius: 0,
                    cubicInterpolationMode: "monotone",
                    data: usersChartData.map((u) => {
                        const [uYear, uMonth, uDay] =
                            u.date_of_created_at.split("-");

                        const d = new Date();
                        d.setFullYear(+uYear, uMonth - 1, +uDay);
                        d.setHours(0, 0, 0);

                        return { x: d, y: u.total_user };
                    }),
                },
            ],
        },
        options: {
            interaction: { mode: "index", intersect: false },
            scales: {
                x: {
                    type: "time",
                    time: {
                        unit: "day",
                    },
                },
            },
        },
    });
}

const refreshButton = document.getElementById("refresh-button");

if (document.body.contains(refreshButton)) {
    refreshButton.addEventListener("click", () => {
        window.location.reload();
    });
}
