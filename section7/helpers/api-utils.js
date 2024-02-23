export async function getFeaturedEvents() {
    const allEvents = await getAllEvents();
    return allEvents.filter((event) => event.isFeatures);
}

export async function getAllEvents() {
    const response = await fetch('https://quickstart-1612785053126-default-rtdb.firebaseio.com/events.json');
    const data = await response.json();

    const events = [];

    for (const key in data) {
        events.push({
            id: key,
            ...data[key]
        })
    }

    return events;
}

export function getFilteredEvents(dateFilter) {
    const {year, month} = dateFilter;

    let filteredEvents = DUMMY_EVENTS.filter((event) => {
        const eventDate = new Date(event.date);
        return eventDate.getFullYear() === year && eventDate.getMonth() === month - 1;
    });

    return filteredEvents;
}

export function getEventById(id) {
    return DUMMY_EVENTS.find((event) => event.id === id);
}
