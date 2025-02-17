
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Letterboxd Gaps</title>
        <link rel="stylesheet" type="text/css" href="main.css">
    </head>
    <body>
        <!-- The main center -->
        <div class="center-wrapper">
            <div class="center">
                <div class="title">
                    <div class="header">
                        <div class="normal">Letterboxd</div>
                    </div>
                    <div class="subtext">GAPS</div>
                </div>
                <p>
                    Expand your film horizons by analyzing your Letterboxd data! Discover countries and languages you're missing, all while highlighting films by female directors.<br><br>
                    To use go to <a href="https://letterboxd.com/settings/data/" target="_blank">https://letterboxd.com/settings/data/</a> and click "Export&nbsp;Your&nbsp;Data".<br>
                    Then <label for="zipInput">drag and drop</label> the .zip file in this window. 
                    <input type="file" name="zipInput" id="zipInput" />
                </p>
                <?php
                    require_once("tmdb.php");

                    // Manually picked 50 notable posters
                    $colors = [
                        152601 => ['red', 'Her'],
                        252171 => ['red', 'A girl walks home alone at night'],
                        284 => ['red/orange', 'The Apartment'],
                        426 => ['orange', 'Vertigo'],
                        9806 => ['red', 'The Incredibles'],
                        843 => ['red', 'In the mood for love'],
                        // 592695 => ['pink', 'Pleasure'],
                        1049638 => ['orange', 'Rye Lane'],
                        73723 => ['orange', 'The lorax'],
                        194 => ['red', 'Amelie'],
                        110 => ['red', 'Red'],
                        // 994108 => ['orange?', 'All of us strangers'],
                        693134 => ['orange', 'DUne 2'],
                        // 290098 => ['orange-yellow', 'The Handmaiden'],
                        3086 => ['yellow', 'The Lady Eve'],
                        814340 => ['yellow', 'Cha Cha Real Smooth'],
                        // 212778 => ['yellow', 'Chef'],
                        89 => ['yellow', 'indiana jones and the last crusade'],
                        773 => ['yellow', 'little miss sunshine'],
                        389 => ['yellow', '12 angry men'],
                        86838 => ['lime green', 'seven psychopaths'],
                        91854 => ['green', 'seawall'],
                        85350 => ['green', 'boyhood'],
                        60308 => ['green', 'moneyball'],
                        1386881 => ['green', 'james acaster hecklers welcome'],
                        995771 => ['light blue', 'la frontera'],
                        965150 => ['blue', 'aftersun'],
                        149870 => ['blue', 'the wind rises'],
                        394117 => ['blue', 'the florida project'],
                        12 => ['blue', 'finding nemo'],
                        398818 => ['blue', 'call me by your name'],
                        372058 => ['blue', 'your name'],
                        38757 => ['yellow', 'tangled'],
                        // 1160164 => ['pink', 'eras tour'],
                        328387 => ['pink/blue', 'nerve'],
                        424781 => ['purple', 'sorry to bother you'],
                        121986 => ['pink', 'frances ha'],
                        354275 => ['purple', 'right now, wrong then'],
                        313369 => ['dark blue/purple', 'la la land'],
                        20139 => ['purple', 'childrens hour'],
                        10315 => ['orange', 'fantastic mr fox'],
                        866398 => ['orange yellow', 'the beekeeper'],
                        10681 => ['purply', 'walle']
                    ];

                    $PDO = getDatabase();
                    $stmt = $PDO->prepare("SELECT poster, primary_color, id, tmdb_id FROM movies WHERE tmdb_id IN (" . implode(',', array_keys($colors)) . ")");
                    $stmt->execute();
                    $posters = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    usort($posters, function($a, $b) {
                        return (float)json_decode($a['primary_color'] ?? "{'h': 0}", true)['h'] <=> (float)json_decode($b['primary_color'] ?? "{'h': 0}", true)['h'];
                    });

                    foreach ($posters as $poster) {
                        echo '<img style="width:100px;" src="' . $poster['poster'] .'" data-tmdb="' . $poster['tmdb_id'] . '" />';
                    }
                ?>
                <script>
                    // Manually created gallery wall using JS code in READMe
                    var positions = [{"id":252171,"width":100,"left":121,"top":-247},{"id":843,"width":90,"left":399,"top":-243},{"id":284,"width":60,"left":142,"top":-74},{"id":194,"width":50,"left":288,"top":-235},{"id":9806,"width":100,"left":263,"top":-136},{"id":426,"width":90,"left":5,"top":-99},{"id":1049638,"width":60,"left":16,"top":-211},{"id":38757,"width":80,"left":-242,"top":-53},{"id":693134,"width":100,"left":-77,"top":51},{"id":73723,"width":120,"left":-141,"top":-142},{"id":89,"width":90,"left":-346,"top":30},{"id":10315,"width":90,"left":-89,"top":367},{"id":866398,"width":110,"left":-225,"top":86},{"id":3086,"width":60,"left":34,"top":330},{"id":814340,"width":110,"left":-352,"top":188},{"id":773,"width":80,"left":-75,"top":221},{"id":389,"width":100,"left":-215,"top":287},{"id":86838,"width":100,"left":24,"top":438},{"id":91854,"width":60,"left":168,"top":505},{"id":85350,"width":60,"left":285,"top":362},{"id":60308,"width":90,"left":151,"top":356},{"id":965150,"width":80,"left":390,"top":354},{"id":995771,"width":50,"left":398,"top":499},{"id":1386881,"width":90,"left":269,"top":475},{"id":149870,"width":60,"left":528,"top":331},{"id":394117,"width":90,"left":617,"top":214},{"id":12,"width":100,"left":497,"top":442},{"id":398818,"width":100,"left":622,"top":374},{"id":328387,"width":80,"left":881,"top":80},{"id":372058,"width":90,"left":747,"top":284},{"id":10681,"width":80,"left":871,"top":222},{"id":313369,"width":130,"left":728,"top":70},{"id":20139,"width":100,"left":596,"top":46},{"id":424781,"width":90,"left":789,"top":-78},{"id":121986,"width":120,"left":643,"top":-155},{"id":354275,"width":90,"left":531,"top":-100},{"id":152601,"width":60,"left":540,"top":-215},{"id":110,"width":70,"left":413,"top":-87}];
                    for (const position of positions) {
                        const item = document.querySelector(`.center img[data-tmdb="${position.id}"]`);
                        item.style.width = `${position.width}px`;
                        item.style.top = `${position.top}px`;
                        item.style.left = `${position.left}px`;
                        item.setAttribute('top', position.top);
                        item.setAttribute('left', position.left);
                    }
                </script>
            </div>
        </div>

        <!-- After uploading, top bar -->
        <div class="nav">
            <div class="title">
                <div class="header">
                    <div class="normal">Letterboxd</div>
                    <div class="progress">
                        <div class="wrapper">
                            <span class="orange">Let</span><span class="green">ter</span><span class="blue">boxd</span>
                        </div>
                    </div>
                </div>
                <div class="subtext">GAPS</div>
            </div>
            <div class="menu">
                <div id="list-select"></div>
                <div id="numMovies"><span class='filter'>0 out of </span><span class='total'>0</span> movies</div>
                <button class="gender" onclick="femaleDirectors()">Female Directors</button>
                <button class="countries" onclick="countries()">Countries</button>
                <button class="languages" onclick="languages()">Languages</button>
                <button class="help" onclick="showHelp()">?</button>
            </div>
        </div>
        <div class="made">
           Made by Alex Beals | <a href="./thanks">Thanks</a> | <a href="https://github.com/dado3212/letterboxd-gaps" target="_blank">Github</a> | <a href="https://letterboxd.com/dado3212/list/letterboxd-gaps/">Posters</a>
        </div>

        <div id="help">
            <div class="modal">
                <button class="help" onclick="hideHelp()">X</button>
                <h2>FAQ</h2>
                <p><b>Q: Why doesn't Letterboxd Gaps also show which countries or languages I <i>have</i> seen movies for?</b><br>
                    A: While Letterboxd Gaps doesn't use the formal Letterboxd API it implicitly uses it through scraping. They (rightfully) deny
                    access for "any usage that recreates current or planned features of our paid subscription tiers". Instead if you want
                    this functionality you can get a <a class="pro" href="https://letterboxd.com/pro/" target="_blank">Pro</a> or <a class="patron" href="https://letterboxd.com/pro/" target="_blank">Patron</a> subscription.
                </p>
                <p><b>Q: Why doesn't the size of my list match Letterboxd's?</b><br>
                    A: To avoid messing up the list appearance the tool automatically removes duplicate movies from the list. This can
                    lower the count, especially when looking at diary lists.
                </p>
            </div>
        </div>

        <div id="languageInfo">
            <h2>Missing Languages</h2>
            <p></p>
            <ol id="languageList"></ol>
         </div>

        <div id="countryInfo">
            <h2>Missing Countries</h2>
            <p></p>
            <div id="svgMap"></div>
            <ol id="countryList"></ol>
         </div>

        <link href="https://cdn.jsdelivr.net/gh/StephanWagner/svgMap@v2.10.1/dist/svgMap.min.css" rel="stylesheet">
        <script src="./svgMap.min.js"></script>
        <style>
            #countryInfo, #languageInfo {
                position: absolute;
                left: -300vw;

                text-align: center;
                margin: 15px 0 20px 0;
            }
            #countryInfo h2, #languageInfo h2 {
                font-family: 'GraphikSemiBold';
                margin: 10px;
            }
            #countryInfo p, #languageInfo p {
                font-family: 'Graphik-Regular-Web';
                max-width: 400px;
                margin: 10px auto;
                color: #9ab;
                font-style: italic;
                font-size: 0.9em;
                line-height: 1.2em;
            }

            #svgMap {
                width: 950px;
                margin: 0 auto;

                display: inline-block;
            }
            #countryList {
                width: 200px;
                max-width: 300px;
                display: inline-block;
                overflow: scroll;
                text-align: left;
                max-height: 29em;
                margin-left: 10px;
                vertical-align: top;
                margin-top: 10px;
            }
            #languageList {
                column-count: 4;
                display: inline-block;
                overflow: scroll;
                text-align: left;
                max-height: 29em;
                margin-left: 10px;
                vertical-align: top;
                margin-top: 10px;
            }
            #countryList, #countryList a, #languageList, #languageList a {
                color: inherit;
                text-decoration: none;
            }
            #countryList a, #languageList a {
                color: #9ab;
                cursor: pointer;
                font-size: 14px;

                font-family: Graphik-Regular-Web, sans-serif;
            }
            #countryList a:hover, #languageList a:hover {
                color: #def;
            }
            #countryList a span, #languageList a span {
                color: #678;
            }
            #countryList li, #languageList li {
                margin: 3px 0;
            }
            ol {
                list-style: none;
            }
            .svgMap-map-wrapper {
                background: #14181c;
                border: 1px solid #303840;
                border-radius: 4px;
                color: #9ab;
            }
            .svgMap-map-wrapper .svgMap-country {
                cursor: pointer;
                stroke: rgba(0, 0, 0, .25);
                stroke-width: 1.5;
                stroke-linejoin: round;
                vector-effect: non-scaling-stroke;
                transition: fill .2s, stroke .2s;
            }
            .svgMap-map-wrapper .svgMap-country:hover {
                stroke: rgba(0, 0, 0, .25);
                fill: #40bcf4;
            }
            .svgMap-tooltip {
                background: #456;
                border-radius: 4px;
                border-bottom: none;
                box-shadow: 0 2px 10px rgba(0, 0, 0, .2);
                color: #9ab;
            }
            .svgMap-tooltip .svgMap-tooltip-content-container {
                padding: 8px 12px;
                position: relative;
            }
            .svgMap-tooltip .svgMap-tooltip-title {
                color: #cde;
                font-family: Graphik-Semibold-Web, sans-serif;
                font-size: 14px;
                font-weight: 400;
                line-height: 1;
                padding: 2px 0;
                text-align: center;
                white-space: nowrap;
            }
            .svgMap-tooltip .svgMap-tooltip-content {
                color: #9ab;
                font-size: 12px;
                line-height: 1;
                margin: 0;
                text-align: center;
                white-space: nowrap;
            }
            .svgMap-tooltip .svgMap-tooltip-content .svgMap-tooltip-no-data {
                color: #9ab;
                padding: 2px 0;
            }
            .svgMap-tooltip .svgMap-tooltip-content table td span {
                color: #9ab;
            }
            .svgMap-tooltip .svgMap-tooltip-pointer:after {
                background: #456;
                border: none;
                border-radius: 3px;
                bottom: 6px;
                content: "";
                height: 20px;
                left: 50%;
                position: absolute;
                transform: translateX(-50%) rotate(45deg);
                width: 20px;
            }
            
            .svgMap-map-controls-wrapper {
                border-radius: 2px;
                bottom: 10px;
                box-shadow: 0 0 0 2px rgba(0,0,0,.1);
                display: flex;
                left: 10px;
                overflow: hidden;
                position: absolute;
                z-index: 1
            }

            .svgMap-map-wrapper .svgMap-map-controls-move, .svgMap-map-wrapper .svgMap-map-controls-zoom {
                background: #2c3440;
                display: flex;
                margin-right: 5px;
                overflow: hidden
            }

            .svgMap-map-controls-move:last-child,.svgMap-map-controls-zoom:last-child {
                margin-right: 0
            }

            .svgMap-control-button {
                cursor: pointer;
                height: 30px;
                position: relative;
                width: 30px
            }

            .svgMap-map-wrapper .svgMap-control-button.svgMap-zoom-button:after, .svgMap-map-wrapper .svgMap-control-button.svgMap-zoom-button:before {
                background: #9ab;
                content: "";
                left: 50%;
                position: absolute;
                top: 50%;
                transform: translate(-50%,-50%);
                transition: background-color .2s
            }
            .svgMap-map-wrapper .svgMap-control-button.svgMap-zoom-button:hover:after, .svgMap-map-wrapper .svgMap-control-button.svgMap-zoom-button:hover:before {
                background: #fff;
            }

            .svgMap-control-button.svgMap-zoom-button:before {
                height: 3px;
                width: 11px
            }

            .svgMap-control-button.svgMap-zoom-button:hover:after,.svgMap-control-button.svgMap-zoom-button:hover:before {
                background: #fff
            }

            .svgMap-control-button.svgMap-zoom-button.svgMap-disabled:after,.svgMap-control-button.svgMap-zoom-button.svgMap-disabled:before {
                background: #ccc
            }

            .svgMap-control-button.svgMap-zoom-in-button:after {
                height: 11px;
                width: 3px
            }
        </style>

        <div id="movies">
        </div>

        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
        <script>
            const listening = true;
            const dropArea = document.querySelector('html');
            // Prevent default behaviors for drag events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, e => e.preventDefault());
            });

            // Don't drag the images
            document.querySelectorAll('img').forEach(img => {
                img.ondragstart = function() { return false; };
            });

            // Highlight area on dragover
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => {
                    dropArea.classList.add('hover');
                    document.querySelectorAll('.center img').forEach(img => {
                        const top = parseInt(img.getAttribute('top'));
                        const left = parseInt(img.getAttribute('left'));
                        img.style.top = (top + (top - 97) * 0.05) + 'px';
                        img.style.left = (left + (left - 253) * 0.05) + 'px';
                    });
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => {
                    dropArea.classList.remove('hover');
                    document.querySelectorAll('.center img').forEach(img => {
                        img.style.top = img.getAttribute('top') + 'px';
                        img.style.left = img.getAttribute('left') + 'px';
                    });
                });
            });

            // Handle file drop and/or select
            const afterAttempt = (files) => {
                if (files.length !== 1) {
                    alert('Please upload a valid .zip file.');
                    return;
                }
                if (files[0].type === 'application/zip') {
                    if (files[0].name.startsWith('letterboxd-')) {
                        uploadFile(files[0]);
                    } else {
                        alert(`${files[0].name} is not an unmodified Letterboxd export file.`);
                    }
                } else {
                    alert(`${files[0].name} is not a zip file.`);
                }
            }
            dropArea.addEventListener('drop', event => {
                afterAttempt(event.dataTransfer.files);
            });

            document.querySelector('#zipInput').addEventListener('change', event => {
                afterAttempt(event.target.files);
            });

            function scrapePendingMovies(uploadId, cb) {
                fetch(`get_movie_info.php?id=${uploadId}`, {
                    method: 'GET',
                })
                .then(response => response.text())
                .then(rawData => {
                    const parsedData = JSON.parse(rawData);
                    if (parsedData['status'] != 'finished') {
                        if (parsedData['status'] == 'failed') {
                            console.log(parsedData['error']);
                        }
                        scrapePendingMovies(uploadId, cb);
                    } else {
                        // Include all of the images and pictures, and rearrange them
                        console.log('finished');
                        cb();
                    }
                });
            }

            const help = document.querySelector('#help');
            help.addEventListener('click', (e) => {
                if (!document.querySelector('#help .modal').contains(e.target)) {
                    help.style.display = 'none';
                }
            })
            function showHelp() {
                help.style.display = 'flex';
            }

            function hideHelp() {
                help.style.display = 'none';
            }

            let showingFemaleDirectors = false;
            function femaleDirectors() {
                if (!showingFemaleDirectors) {
                    // Highlight them
                    document.querySelectorAll('.movie:not([data-female])').forEach(poster => {
                        poster.style.opacity = 0.2;
                    });
                    const numFilter = document.querySelector('#numMovies .filter');
                    numFilter.innerHTML = `${document.querySelectorAll('.movie[data-female]').length} out of `;
                    numFilter.style.display = 'initial';
                } else {
                    // Remove highlighting
                    document.querySelectorAll('.movie:not([data-female])').forEach(poster => {
                        poster.style.opacity = 1.0;
                    });
                    const numFilter = document.querySelector('#numMovies .filter');
                    numFilter.style.display = 'none';
                }
                showingFemaleDirectors = !showingFemaleDirectors;
            }

            let isShowingCountries = false;
            function countries() {
                isShowingCountries = !isShowingCountries;
                const countryInfo = document.querySelector('#countryInfo');
                if (isShowingCountries) {
                    countryInfo.style.position = 'inherit';
                } else {
                    countryInfo.style.position = 'absolute';
                }
            }

            let isShowingLanguages = false;
            function languages() {
                isShowingLanguages = !isShowingLanguages;
                const languageInfo = document.querySelector('#languageInfo');
                if (isShowingLanguages) {
                    languageInfo.style.position = 'inherit';
                } else {
                    languageInfo.style.position = 'absolute';
                }
            }

            function transitionToAnalysis() {
                document.querySelector('.made').style.display = 'none';
                // Fade out the imgs
                document.querySelectorAll('.center img').forEach(img => {
                    // Offset them so it's a staggered effect
                    const randomOffset = Math.random() * 0.5;
                    img.style.transition = `top 0.3s, left 0.3s, opacity ${2 - randomOffset}s`;
                    img.style.transitionDelay = `${randomOffset}s`;
                    img.style.transitionProperty = 'opacity';
                    img.style.opacity = '0%';
                });
                document.querySelector('.center p').style.opacity = '0%';

                setTimeout(() => {
                    // Animate the title up to the top
                    const header = document.querySelector('.center .header');
                    header.style.top = (-header.getBoundingClientRect().top) + 'px';
                    header.style.left = '-127px';
                    // Animate gaps to the top as well
                    const subtext = document.querySelector('.center .subtext');
                    subtext.style.top = (-subtext.getBoundingClientRect().top + 20.25) + 'px';
                    subtext.style.letterSpacing = '1em';
                    subtext.style.left = '179px';

                    setTimeout(() => {
                        document.querySelector('.center-wrapper').style.display = 'none';
                        document.querySelector('.nav').style.display = 'initial';
                    }, 2000);
                }, 1500); // takes 2s for the images to fade
            }

            let allData = [];
            let diaryLists = new Set();
            let allCountries = {};
            let allLanguages = {};

            function swapList(index) {
                const data = allData[index];

                const countryButton = document.querySelector('button.countries');
                if (diaryLists.has(index)) {
                    countryButton.disabled = true;
                    countryButton.title = 'All diary movies have been watched, so there are no countries you haven\'t seen movies from.';
                } else {
                    countryButton.disabled = false;
                    countryButton.title = '';
                }

                const languageButton = document.querySelector('button.languages');
                if (diaryLists.has(index)) {
                    languageButton.disabled = true;
                    languageButton.title = 'All diary movies have been watched, so there are no languages you haven\'t seen movies in.';
                } else {
                    languageButton.disabled = false;
                    languageButton.title = '';
                }

                movies = data.movies;

                const container = document.getElementById('movies');
                container.innerHTML = '';

                // Set up map
                // If you're viewing your watched list, this is all countries you haven't seen
                let movieCountData;
                let movieLanguageCountData;
                let watchedCountries = new Set();
                let watchedLanguages = new Set();
                if (data['name'] == 'Watched') {
                    movieCountData = {...allCountries};
                    movieLanguageCountData = {...allLanguages};
                    movies.forEach(movie => {
                        if (movie.countries) {
                            movie.countries.forEach(country => {
                                if (country in movieCountData) {
                                    delete movieCountData[country];
                                }
                            });
                        }
                        if (movie.language in movieLanguageCountData) {
                            delete movieLanguageCountData[movie.language];
                        }
                    });
                } else {
                    // Get all of the countries/languages that you've already seen from the watchlist
                    allData[0].movies.forEach(movie => {
                        if (movie.countries) {
                            movie.countries.forEach(country => {
                                watchedCountries.add(country);
                            });
                        }
                        if (movie.language) {
                            watchedLanguages.add(movie.language);
                        }
                    });
                    // Filter your current list to countries/languages you haven't seen. 
                    movieCountData = {};
                    movieLanguageCountData = {};
                    movies.forEach(movie => {
                        if (movie.countries) {
                            movie.countries.forEach(country => {
                                if (watchedCountries.has(country)) {
                                    return;
                                }
                                if (country in movieCountData) {
                                    movieCountData[country]['num_movies'] += 1;
                                } else {
                                    movieCountData[country] = {
                                        'num_movies': 1
                                    };
                                }
                            });
                        }
                        if (movie.language) {
                            if (watchedLanguages.has(movie.language)) {
                                return;
                            }
                            if (movie.language in movieLanguageCountData) {
                                movieLanguageCountData[movie.language]['num_movies'] += 1;
                            } else {
                                movieLanguageCountData[movie.language] = {
                                    'num_movies': 1
                                };
                            }
                        }
                    });
                }

                if (Object.keys(movieCountData).length == 0) {
                    countryButton.disabled = true;
                    countryButton.title = 'You\'ve seen movies from all countries represented by this list.';
                }

                if (Object.keys(movieLanguageCountData).length == 0) {
                    languageButton.disabled = true;
                    languageButton.title = 'You\'ve seen movies in all languages represented by this list.';
                }

                const svg = document.getElementById('svgMap');
                svg.innerHTML = '';
                const svgObject = new svgMap({
                    targetElementID: 'svgMap',
                    colorMin: '#007733',
                    colorMax: '#00E054',
                    colorNoData: '#303C44',
                    hideFlag: true,
                    initialZoom: 1,
                    minZoom: 1,
                    noDataText: (country) => {
                        if (data['name'] == 'Watched') {
                            return 'Already seen';
                        } else {
                            if (watchedCountries.has(country)) {
                                return 'Already seen';
                            } else {
                                return 'No movies in list';
                            }
                        }
                    },
                    data: {
                        data: {
                            num_movies: {
                                name: 'Films:',
                                format: '{0}',
                                thousandSeparator: ','
                            }
                        },
                        applyData: 'num_movies',
                        values: movieCountData,
                    },
                });

                const countryList = document.querySelector('#countryList');
                countryList.innerHTML = '';
                movieCountData = Object.fromEntries(
                    Object.entries(movieCountData).sort(([, a], [, b]) => b.num_movies - a.num_movies)
                );

                if (data['name'] == 'Watched') {
                    document.querySelector('#countryInfo p').innerHTML = `
                    You haven't seen any movies from these countries.
                    Click a country on the map or in the list on the right to go to a full 
                    list of movies from that country. Add some to your watchlist!`;
                } else {
                    document.querySelector('#countryInfo p').innerHTML = `
                    This shows movies in this list that are from countries you've never seen anything from.
                    Click a country on the map or in the list on the right to highlight
                    the movies from that country.`;
                }

                let currentSelectedCountry = null;
                clickCountry = (clickedCountry) => {
                    if (data['name'] == 'Watched') {
                        window.open(allCountries[clickedCountry]['url'], '_blank');
                        return;
                    }

                    if (clickedCountry == currentSelectedCountry || !(clickedCountry in movieCountData)) {
                        document.querySelectorAll('.movie').forEach(poster => {
                            poster.style.opacity = 1.0;
                        });
                        currentSelectedCountry = null;
                    } else {
                        currentSelectedCountry = clickedCountry;
                        document.querySelectorAll('.movie').forEach(poster => {
                            const dataCountries = poster.getAttribute('data-countries');
                            if (dataCountries && dataCountries.includes(clickedCountry)) {
                                poster.style.opacity = 1.0;
                            } else {
                                poster.style.opacity = 0.2;
                            }
                        });
                    }
                };

                for (country in movieCountData) {
                    countryList.innerHTML += `<li>
                        <a onclick="clickCountry('${country}');">${allCountries[country]['full_name']}
                            <span>${movieCountData[country].num_movies.toLocaleString()}</span>
                        </a>
                    </li>`;
                }

                var svgCountries = svg.querySelector('.svgMap-map-image').querySelectorAll('.svgMap-country');
                for (var i = 0; i < svgCountries.length; i++) {
                    const country = svgCountries[i];

                    country.addEventListener('click', function(e) {
                        e.preventDefault();

                        const clickedCountry = country.getAttribute('data-id');
                        clickCountry(clickedCountry);
                    });
                }

                // Language setup
                const languageList = document.querySelector('#languageList');
                languageList.innerHTML = '';
                movieLanguageCountData = Object.fromEntries(
                    Object.entries(movieLanguageCountData).sort(([, a], [, b]) => b.num_movies - a.num_movies)
                );

                if (data['name'] == 'Watched') {
                    document.querySelector('#languageInfo p').innerHTML = `
                    You haven't seen any movies in these languages.
                    Click a language in the list on the right to go to a full list 
                    of movies in that language. Add some to your watchlist!`;
                } else {
                    document.querySelector('#languageInfo p').innerHTML = `
                    This shows movies in this list that are in languages you've never seen anything in.
                    Click a language in the list on the right to highlight
                    the movies in that language.`;
                }

                let currentlySelectedLanguage = null;
                clickLanguage = (clickedLanguage) => {
                    if (data['name'] == 'Watched') {
                        window.open(allLanguages[clickedLanguage]['url'], '_blank');
                        return;
                    }

                    if (clickedLanguage == currentlySelectedLanguage || !(clickedLanguage in movieLanguageCountData)) {
                        document.querySelectorAll('.movie').forEach(poster => {
                            poster.style.opacity = 1.0;
                        });
                        currentlySelectedLanguage = null;
                    } else {
                        currentlySelectedLanguage = clickedLanguage;
                        document.querySelectorAll('.movie').forEach(poster => {
                            const dataLanguage = poster.getAttribute('data-language');
                            if (dataLanguage == clickedLanguage) {
                                poster.style.opacity = 1.0;
                            } else {
                                poster.style.opacity = 0.2;
                            }
                        });
                    }
                };

                for (country in movieLanguageCountData) {
                    if (country in allLanguages) {
                        languageList.innerHTML += `<li>
                            <a onclick="clickLanguage('${country}');">${allLanguages[country]['full_name']}
                                <span>${movieLanguageCountData[country].num_movies.toLocaleString()}</span>
                            </a>
                        </li>`;
                    }
                }

                // Clear country UI
                isShowingCountries = false;
                const countryInfo = document.querySelector('#countryInfo');
                countryInfo.style.position = 'absolute';

                // Clear language UI
                isShowingLanguages = false;
                const languageInfo = document.querySelector('#languageInfo');
                languageInfo.style.position = 'absolute';

                // Clear gender selector
                const numFilter = document.querySelector('#numMovies .filter');
                numFilter.style.display = 'none';
                showingFemaleDirectors = false;

                // Clear tippy
                const tippyRoot = document.querySelector('div[data-tippy-root')?.remove();

                let numTotal = 0;
                let numWomen = 0;
                let countries = {};
                let languages = {};

                document.querySelector('.nav #numMovies .total').innerHTML = movies.length.toLocaleString();

                function calculateBestFit(w, h, n, ratio) {
                    let bestWidth = 0;
                    let bestColumns = 0;
                    let bestRows = 0;

                    for (let c = 1; c <= n; c++) {
                        let r = Math.ceil(n / c); // Calculate rows for current column count
                        const x = Math.min(w / (c * 1), h / (r * ratio)); // Calculate the scale-independent width x
                        const y = x * ratio; // Corresponding height
                        if (c * x <= w && r * y <= h) { // Check if the configuration fits
                            if (x > bestWidth) { // Update if this scale is better
                                bestWidth = x;
                                bestColumns = c;
                                bestRows = r;
                            }
                        }
                    }

                    return {
                        imageWidth: bestWidth,
                        numRows: bestRows,
                        numCols: bestColumns,
                    };
                }

                let centerX, centerY, radiusScale;
                const width = window.innerWidth;
                const height = window.innerHeight - 120.5; // nav height
                const ratio = 138/92;

                let { imageWidth, numRows, numCols } = calculateBestFit(width, height, movies.length, ratio);
                imageWidth = Math.min(imageWidth, 200);
                imageWidth = imageWidth - 4;
                const imageHeight = imageWidth * ratio;

                let styleSheet = document.querySelector('#movieStyleSheet');
                if (styleSheet) {
                    styleSheet.textContent = `
                    #movies .movie {
                        width: ${imageWidth}px;
                        height: ${imageHeight}px;
                    }
                    `;
                } else {
                    styleSheet = document.createElement('style');
                    styleSheet.id = 'movieStyleSheet';
                    styleSheet.textContent = `
                    #movies .movie {
                        width: ${imageWidth}px;
                        height: ${imageHeight}px;
                    }
                    `;
                    document.head.appendChild(styleSheet);
                }

                movies.forEach(movie => {
                    const movieName = `${movie.movie_name} (${movie.year})`;
                    const movieDiv = document.createElement('div');
                    movieDiv.className = 'movie';
                    movieDiv.onclick = () => {
                        window.open(movie.letterboxd_url, '_blank');
                    };
                    if (movie.poster) {
                        if (movie.poster.startsWith('/')) {
                            movieDiv.innerHTML = `
                                <img src="https://image.tmdb.org/t/p/w92${movie.poster}" alt="${movieName}">
                            `;
                        } else {
                            movieDiv.innerHTML = `
                                <img src="${movie.poster}" alt="${movieName}">
                            `;
                        }
                    } else {
                        movieDiv.className += ' missing';
                        movieDiv.innerHTML = `
                            <div style="font-size: ${3 * imageWidth / movieName.length}px">
                                <span>${movieName}</span>
                            </div>
                        `;
                    }
                    // shorthand for "we have the tmdb info"
                    if (movie.tmdb_id) {
                        if (movie.has_female_director) {
                            numWomen += 1;
                            movieDiv.setAttribute('data-female', '1');
                        }
                        numTotal += 1;
                        if (movie.language) {
                            movieDiv.setAttribute('data-language', movie.language);
                            if (!(movie.language in languages)) {
                                languages[movie.language] = 0;
                            }
                            languages[movie.language] += 1;
                        }
                        if (movie.countries) {
                            movieDiv.setAttribute('data-countries', movie.countries.join(','));
                            for (const country of movie.countries) {
                                if (!(country in countries)) {
                                    countries[country] = 0;
                                }
                                countries[country] += 1;
                            }
                        }
                    }
                    container.appendChild(movieDiv);
                    // Add in the tooltip if the image is too small
                    if (imageWidth < 70) {
                        tippy(movieDiv, {
                            animation: 'scale',
                            content: `<b>${movie.movie_name} (${movie.year})</b><br>${movieDiv.innerHTML}`,
                            allowHTML: true,
                            followCursor: true,
                            duration: 0,
                            maxWidth: 170, // image width + 20 for borders
                        });
                    }
                });

                // And make it so the new images can't be dragged
                document.querySelectorAll('img').forEach(img => {
                    img.ondragstart = function() { return false; };
                });
            }

            let bodyClickListener = null;
            function tryToUpload(formData) {
                const container = document.getElementById('movies');
                container.innerHTML = '';

                // Clear tippy
                const tippyRoot = document.querySelector('div[data-tippy-root')?.remove();

                // Set up the list selection (hide it by default until we know how many we're dealing with)
                const listSelect = document.getElementById('list-select');
                listSelect.innerHTML = '';
                listSelect.style.display = 'none';
                
                fetch('get_watched_list.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(rawData => {
                    let data = JSON.parse(rawData);

                    allCountries = data.countries;
                    allLanguages = data.languages;
                    const uploadId = data.upload_id;
                    const shouldUpload = data.should_upload;
                    data = data.movies;
                    // Set up the list selector, which is composed of two pieces
                    const listSelect = document.getElementById('list-select');
                    listSelect.innerHTML = '';
                    // One, the "currently selected view"
                    listSelect.style.display = 'block';
                    let innerHTML = `
                        <div class="selected">
                            <span class="name">${data[0].name}</span><span class="number">${data[0].movies.length}</span></div>
                        </div>
                        <div class="dropdown">
                    `;
                    let dataIndex = 0;
                    allData = [];
                    diaryLists = new Set();

                    // Two, the dropdown menu that unfolds
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].type == 'group') {
                            innerHTML += `
                            <div class="group"><span class="name">${data[i].name}</span>
                                <div class="sublist">`;
                            for (var j = 0; j < data[i].sublists.length; j++) {
                                innerHTML += `<div data-list="${dataIndex}"><span class="name">${data[i].sublists[j].name}</span><span class="number">${data[i].sublists[j].movies.length}</span></div>`;
                                allData.push(data[i].sublists[j]);
                                if (data[i].name == 'Diary') {
                                    diaryLists.add(dataIndex);
                                }
                                dataIndex += 1;
                            }
                            innerHTML += '</div></div>';
                        } else {
                            innerHTML += `<div data-list="${dataIndex}"><span class="name">${data[i].name}</span><span class="number">${data[i].movies.length}</span></div>`;
                            allData.push(data[i]);
                            dataIndex += 1;
                        }
                    }
                    listSelect.innerHTML = innerHTML + '</div>';
                    const selected = document.querySelector('#list-select .selected');

                    selected.onclick = () => {
                        listSelect.classList.toggle('opened');
                    };
                    document.querySelectorAll('.dropdown div:not(.group):not(.sublist)').forEach(list => {
                        list.onclick = () => {
                            listSelect.classList.remove('opened');
                            selected.innerHTML = list.innerHTML;
                            swapList(Number(list.dataset.list));
                        };
                    });
                    document.querySelectorAll('.dropdown .group').forEach(group => {
                        group.onclick = () => {
                            group.classList.toggle('opened');
                        };
                    });
                    if (bodyClickListener != null) {
                        document.body.removeEventListener('click', bodyClickListener);
                    }
                    bodyClickListener = (e) => {
                        if (listSelect.classList.contains('opened') && !listSelect.contains(e.target)) {
                            listSelect.classList.remove('opened');
                        }
                    };
                    document.body.addEventListener('click', bodyClickListener);
                    swapList(0);

                    const progressBar = document.querySelector('.nav .progress');
                    progressBar.style.height = '0%';

                    if (uploadId) {
                        if (shouldUpload) {
                            scrapePendingMovies(uploadId, () => {
                                tryToUpload(formData);
                            });
                        }
                        // 30% to 81%
                        progressBar.style.height = '30%';
                        const header = document.querySelector('.nav .header');

                        const interval = setInterval(() => {
                            fetch('poll_status.php?id=' + uploadId)
                            .then(response => response.json())
                            .then(data => {
                                progressBar.style.height = `${30 + (81 - 30) * (data.done / data.total)}%`;
                                header.title = `Processed ${data.done.toLocaleString()} out of ${data.total.toLocaleString()} movies.`;
                                if (data.done == data.total) {
                                    progressBar.style.height = '100%';
                                    clearTimeout(interval);
                                }
                            });
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            // File upload
            function uploadFile(file) {
                const formData = new FormData();
                formData.append('file', file);

                // Make sure that we hide the gender breakdown
                const numFilter = document.querySelector('#numMovies .filter');
                numFilter.style.display = 'none';
                showingFemaleDirectors = false;

                transitionToAnalysis();

                tryToUpload(formData);
            }
        </script>
    </body>
</html>