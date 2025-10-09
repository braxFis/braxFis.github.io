const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
let currentDate = new Date();
let events = JSON.parse(localStorage.getItem('events')) || {};
let selectedDay = null;

// Helper function to generate the days for a specific month
function generateDays(year, month) {
    const date = new Date(year, month, 1);
    const days = [];
    const firstDayOfMonth = date.getDay();
    const lastDateOfMonth = new Date(year, month + 1, 0).getDate();

    // Fill the first row with empty cells for days before the 1st of the month
    for (let i = 0; i < firstDayOfMonth; i++) {
        days.push(null);
    }

    // Add the actual days of the month
    for (let day = 1; day <= lastDateOfMonth; day++) {
        days.push(day);
    }

    return days;
}

// Update the calendar display
function updateCalendar() {
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();
    const days = generateDays(currentYear, currentMonth);

    // Update month-year header
    document.getElementById('month-year').textContent = `${currentDate.toLocaleString('default', { month: 'long' })} ${currentYear}`;

    // Update days of week header
    const daysOfWeekElement = document.getElementById('days-of-week');
    daysOfWeekElement.innerHTML = '';
    daysOfWeek.forEach(day => {
        const dayElement = document.createElement('div');
        dayElement.textContent = day;
        daysOfWeekElement.appendChild(dayElement);
    });

    // Update days grid
    const daysGridElement = document.getElementById('days-grid');
    daysGridElement.innerHTML = '';
    days.forEach(day => {
        const dayElement = document.createElement('div');
        dayElement.textContent = day;
        dayElement.className = 'calendar-day';
        dayElement.onclick = () => handleDayClick(day);
        if (day) {
            dayElement.style.backgroundColor = selectedDay === day ? '#f0f0f0' : 'transparent';
            if (events[`${currentYear}-${currentMonth + 1}-${day}`]) {
                dayElement.style.fontWeight = 'bold';
            }
        }
        daysGridElement.appendChild(dayElement);
    });
}

// Navigate to the previous month
function goToPreviousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateCalendar();
}

// Navigate to the next month
function goToNextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateCalendar();
}

// Handle day click
function handleDayClick(day) {
    if (day) {
        selectedDay = day;
        document.getElementById('event-title').textContent = `Add Event for ${selectedDay}/${currentDate.getMonth() + 1}/${currentDate.getFullYear()}`;
        document.getElementById('event-form').style.display = 'block';
        updateEventList();
    }
}

// Add event for selected day
function addEvent() {
    const eventInput = document.getElementById('event-input');
    const eventText = eventInput.value.trim();

    if (eventText && selectedDay !== null) {
        const eventKey = `${currentDate.getFullYear()}-${currentDate.getMonth() + 1}-${selectedDay}`;
        if (!events[eventKey]) {
            events[eventKey] = [];
        }
        events[eventKey].push(eventText);

        localStorage.setItem('events', JSON.stringify(events));
        eventInput.value = ''; // Clear input
        updateEventList();
    }
}

// Update event list for selected day
function updateEventList() {
    const eventListElement = document.getElementById('events-list');
    const eventKey = `${currentDate.getFullYear()}-${currentDate.getMonth() + 1}-${selectedDay}`;
    eventListElement.innerHTML = '';
    if (events[eventKey]) {
        events[eventKey].forEach((event, index) => {
            const eventItem = document.createElement('div');
            eventItem.textContent = event;
            eventListElement.appendChild(eventItem);
        });
    }
}

// Initial calendar render
updateCalendar();