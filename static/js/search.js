const $searchInput = $("search_input");

$searchInput.onkeyup = () => {
    const s = $searchInput.value;
    if (s.length >= 2) x("GET", `/controller/search.php?s=${s}`, true, getResults);
};

function getResults(r) {
    console.log(r);
}