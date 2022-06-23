import autoComplete from "@tarekraafat/autocomplete.js/dist/autoComplete";

const autoCompleteJS = new autoComplete({
    debounce: 300,
    submit: true,
    data: {
        src: async (query) => {
            const source = await fetch('/api/movie/search?query=' + query);
            const result = await source.json();

            return result.results.movies;
        },
        keys: ["title"],
        cache: false
    },
    resultsList: {
        element: (list, data) => {
            const info = document.createElement("p");
            if (data.results.length > 0) {
                info.innerHTML = `<strong>${data.results.length}</strong> sur <strong>${data.matches.length}</strong> résulats trouvé`;
            } else {
                info.innerHTML = `Aucun résultat"</strong>`;
            }
            list.prepend(info);
        },
        noResults: true,
        maxResults: 15,
        tabSelect: true
    },
    resultItem: {
        highlight: true
    }
});

autoCompleteJS.input.addEventListener("selection", function (event) {
    const feedback = event.detail;
    const selection = feedback.selection.value;

    autoCompleteJS.input.blur();
    autoCompleteJS.input.value = selection[feedback.selection.key];

    window.location.href = '/movie/' + selection.id
});

document.getElementById('autoComplete').addEventListener('keypress', function (event) {
    if (event.key === "Enter") {
        event.preventDefault();

        window.location.href = '/movie/search?query=' + event.target.value
    }
});