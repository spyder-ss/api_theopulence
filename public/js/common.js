$(document).ready(function () {
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }

    initializeNotifications();

    if (typeof Chart !== "undefined") {
        initializeCharts();
    }

    // initializeNavigation();

    initializeEventListeners();
});

let currentMode = "admin";

const bookingTrendsData = {
    labels: ["May", "Jun", "Jul", "Aug", "Sep", "Oct"],
    datasets: [
        {
            label: "Bookings",
            data: [45, 52, 68, 71, 58, 63],
            borderColor: "#004d40",
            backgroundColor: "rgba(0, 77, 64, 0.1)",
            tension: 0.4,
        },
        {
            label: "Revenue ($)",
            data: [125000, 148000, 195000, 201000, 167000, 182000],
            borderColor: "#10b981",
            backgroundColor: "rgba(16, 185, 129, 0.1)",
            tension: 0.4,
            yAxisID: "y1",
        },
    ],
};

const bookingStatusData = {
    labels: ["Confirmed", "Pending", "Cancelled"],
    datasets: [
        {
            data: [847, 156, 43],
            backgroundColor: ["#10b981", "#f59e0b", "#ef4444"],
        },
    ],
};

const propertyPerformanceData = {
    labels: [
        "Sunset Villa",
        "Ocean View Suite",
        "Mountain Lodge",
        "Garden Estate",
        "Lakeside Retreat",
    ],
    datasets: [
        {
            label: "Revenue ($)",
            data: [287000, 245000, 198000, 176000, 142000],
            backgroundColor: "#004d40",
        },
    ],
};

function initializeCharts() {
    const bookingTrendsCtx = document.getElementById("bookingTrendsChart");
    if (bookingTrendsCtx) {
        new Chart(bookingTrendsCtx.getContext("2d"), {
            type: "line",
            data: bookingTrendsData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: "#6b7280",
                        },
                    },
                    y1: {
                        beginAtZero: true,
                        position: "right",
                        ticks: {
                            color: "#6b7280",
                            callback: function (value) {
                                return "$" + value / 1000 + "K";
                            },
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: "#6b7280",
                        },
                    },
                },
            },
        });
    }

    const bookingStatusCtx = document.getElementById("bookingStatusChart");
    if (bookingStatusCtx) {
        new Chart(bookingStatusCtx.getContext("2d"), {
            type: "doughnut",
            data: bookingStatusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }

    const propertyPerformanceCtx = document.getElementById(
        "propertyPerformanceChart"
    );
    if (propertyPerformanceCtx) {
        new Chart(propertyPerformanceCtx.getContext("2d"), {
            type: "bar",
            data: propertyPerformanceData,
            options: {
                indexAxis: "y",
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            color: "#6b7280",
                            callback: function (value) {
                                return "$" + value / 1000 + "K";
                            },
                        },
                    },
                    y: {
                        ticks: {
                            color: "#6b7280",
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: "#6b7280",
                        },
                    },
                },
            },
        });
    }
}

function initializeNavigation() {
    $(".nav-link").on("click", function (e) {
        e.preventDefault();

        $(".nav-link").removeClass("active");

        $(this).addClass("active");

        const pageName = $(this).find("span").text();
        $(".dashboard-title").text(pageName);

        if ($(this).data("page") !== "dashboard") {
            $(".dashboard-subtitle").text(
                "This section is under construction."
            );
        } else {
            $(".dashboard-subtitle").text(
                "Welcome back! Here's a comprehensive view of your business performance."
            );
        }
    });
}

function initializeEventListeners() {
    $(".sidebar-toggle").on("click", toggleSidebar);
    $(".sidebar-overlay").on("click", toggleSidebar);

    // Toggle submenus on nav-link click
    $('.nav-link').on('click', function(e) {
        var $this = $(this);
        var $item = $this.closest('.nav-item');
        if ($(this).siblings('.nav-submenu').length > 0) {
            e.preventDefault();
            $item.toggleClass('active');
        }
    });

    $(document).on("click", function (event) {
        const profileDiv = $(".user-profile")[0];
        const profileDropdown = $("#profileDropdown")[0];
        const profileChevron = $("#profile-chevron")[0];
        const notificationBtn = $(".notification-btn")[0];
        const notificationDropdown = $("#notificationDropdown")[0];

        if (profileDiv && profileDropdown && profileChevron) {
            if (
                !profileDiv.contains(event.target) &&
                !profileDropdown.contains(event.target)
            ) {
                $(profileDropdown).removeClass("show");
                $(profileChevron).css("transform", "rotate(0deg)");
            }
        }

        if (notificationBtn && notificationDropdown) {
            if (
                !notificationBtn.contains(event.target) &&
                !notificationDropdown.contains(event.target)
            ) {
                $(notificationDropdown).removeClass("show");
                $(notificationBtn).removeClass("active");
            }
        }
    });

    $(".logout-link").on("click", function (event) {
        event.preventDefault();
        $(this).closest("form").submit();
    });
}

function initializeNotifications() {
    $(".mark-read").on("click", markAllRead);

    $(document).on("click", ".notification-close", function () {
        removeNotification(this);
    });

    updateNotificationDot();
}

function setDashboardMode(mode) {
    currentMode = mode;

    const adminDashboard = $("#adminDashboard")[0];
    const guestDashboard = $("#guestDashboard")[0];
    const adminBtn = $(".mode-btn:first-child")[0];
    const guestBtn = $(".mode-btn:last-child")[0];

    if (mode === "admin") {
        if (adminDashboard) adminDashboard.style.display = "flex";
        if (guestDashboard) guestDashboard.style.display = "none";
        if (adminBtn) adminBtn.classList.add("active");
        if (guestBtn) guestBtn.classList.remove("active");
    } else {
        if (adminDashboard) adminDashboard.style.display = "none";
        if (guestDashboard) guestDashboard.style.display = "block";
        if (adminBtn) adminBtn.classList.remove("active");
        if (guestBtn) guestBtn.classList.add("active");
    }
}

function toggleSidebar() {
    $(".sidebar").toggleClass("open");
    $(".sidebar-overlay").toggleClass("show");
}

function toggleProfileDropdown() {
    const dropdown = $("#profileDropdown");
    const chevron = $("#profile-chevron");
    dropdown.toggleClass("show");
    chevron.css(
        "transform",
        dropdown.hasClass("show") ? "rotate(180deg)" : "rotate(0deg)"
    );
}

function toggleNotificationDropdown() {
    const dropdown = $("#notificationDropdown");
    const btn = $(".notification-btn");
    dropdown.toggleClass("show");
    btn.toggleClass("active");
    updateNotificationDot();
}

function markAllRead() {
    $(".notification-item.unread").removeClass("unread");
    updateNotificationDot();
}

function removeNotification(btn) {
    $(btn).closest(".notification-item").remove();
    updateNotificationDot();
}

function updateNotificationDot() {
    const unreadCount = $(".notification-item.unread").length;
    const dot = $("#notificationDot");
    if (unreadCount > 0) {
        dot.show();
    } else {
        dot.hide();
    }
}
