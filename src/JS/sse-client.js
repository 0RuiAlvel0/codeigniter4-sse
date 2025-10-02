if (!!window.EventSource) {
    const source = new EventSource('/sse/stream');

    source.addEventListener('event_name1', function(e) {
        const data = JSON.parse(e.data);
        // Add code here, e.g. alert('Event 1 triggered at ');
    });

    source.addEventListener('event_name2', function(e) {
        const data = JSON.parse(e.data);
        // Add code here, e.g. alert('Event 2 triggered ');
    });
}
