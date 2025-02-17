# letterboxd-gaps
PHP website for gaps in Letterboxd watching

<img width="1501" alt="Screenshot 2025-01-04 at 5 00 18 PM" src="https://github.com/user-attachments/assets/57721a07-aada-42fd-b517-9cb962c24883" />

<img width="1311" alt="Screenshot 2025-01-05 at 10 05 21 PM" src="https://github.com/user-attachments/assets/37f4609b-8e40-4d48-bbcc-73a8e3d0a23c" />

Still very much WIP.

TODO:
- Fix state memory around multiple interactions (click to watchlist, countries, click a country, click to close country, toggle female and then toggle off female directors)

Low pri cleanup
- Fix README
- Clean up Thanks page
- Properly separate out CSS and JS into separate files
- Remove unnecessary SVG map styling rules

Stretch
- Better animation for gender splits where the posters rearrange
- Ditto for country selection
- Live push images as they come?
- Consolidate polling and get_movie_info
- Handle some sort of "watched" affordance for breaking down lists
- Color sorting that matches perception (need to read some more papers around this)

**secret.php**
```
define('TMDB_API_KEY', '<key>');
define('PROCESS_KEY', '<key2>');
define('SCRAPE_COUNTRIES_KEY', '<key3>');
```

Use `CREATE_DBS.sql` to create the tables.

Crontab to scrape countries/language counts daily:
```
00 15 * * * php /var/www/alexbeals.com/public_html/projects/letterboxd/scrape_countries.php <key3>
```

https://github.com/keplerg/color-extract/blob/main/index.php

Color Science:
Dominant color via dominant hue - https://www.sciencedirect.com/science/article/pii/S0042698922000840?pes=vor&utm_source=wiley&getft_integrator=wiley

https://onlinelibrary.wiley.com/doi/10.1002/col.22485

25 perceptually distinct colors, which were also displayed in pseudorandom order. These colors were extracted from images using the k-mean clustering algorithm in CAM02-UCS color space. Initial cluster centroid positions (seeds) were selected uniformly at random from the image's color gamut, while the resulting colors were snapped to the nearest color in the image. By using uniformly selected seeds, we were able to extract colors of all color categories in the image. We have tested different sizes of extracted colors (10, 15, 25, 35, 50) and come to the conclusion that 25 colors are sufficient so that no key color is missing and resulted colors are not too similar.

We used parameters recommended by Moroney40 for transforming images from sRGB into CIECAM02 coordinates: LA = 4, white point = D65, Yb = 20, and dim surround.

In total, we computed 137 features, which can be subcategorized into six categories that according to our research review have an impact on perception of prominent colors: color saliency, hue dominance, color coverage, color properties, color diversity, and color span. Within each category, we defined several matrices and used different parameters. To reduce noise in the images and eliminate small variations between neighboring pixels, images were convolved with filter used in S-CIELAB to simulate lower spatial acuity of the HVS,42 before computing the features.

As can be seen from Table 2, three of 10 highly ranked features refer to the color coverage—soft recoloring error, segment soft- and hard-recoloring error. It appears that color coverage is one of the most important factors in the perception of prominent colors. However, all of them include normalization based on the saliency map. This implies that information about color coverage alone is not sufficient and must be adjusted with other factors that are included in saliency models (please note that different saliency models were utilized in these features—Judd and GBVS). In addition, one of these features works on a pixel level, while the other two work on a segment level. It seems that color coverage is important on both dimensions—at lower and higher acuity (resolution).

The next features important for the perception of prominent colors are those concerning a specific color property. The most influential ones are color lightness and chroma. According to our results, the observers on average tend to select colors with a higher mean lightness relative to other colors in the image. In addition, the observers in general selected at least one color with a high chroma value.

This is in line with the analysis of observers' data, which clearly implies that the observers tend to select diverse prominent colors. The weights of both palette diversity features are negative, indicating the negative correlation with the score.

First, during the psychophysical experiment, some observers complained about the limitation of color selection. In particular, some saturated colors were missing or were “washed out.”

It would also be interesting to compare our model with other methods (eg, clustering methods, histogram-based methods) or available solutions (eg, TinEye Lab, Colormind, Canva, Color Thief) for extracting colors from the image.

Only 54%.

http://colormind.io/blog/extracting-colors-from-photos-and-video/ -> Uses a GAN for filtering pallette generation
https://labs.tineye.com/color/925b7924ef2cae34ff1f9c9041c1f5a23e13a99c?ignore_background=True&ignore_interior_background=True&width=92&height=138&scroll_offset=484
https://lokeshdhakar.com/projects/color-thief/

six technically defined dimensions of color appearance: brightness (luminance), lightness, colorfulness, chroma, saturation, and hue.

CIECAM02 (maybe https://github.com/primozw/ui-ciecam02-app/tree/main?)
Brightness is the subjective appearance of how bright an object appears given its surroundings and how it is illuminated. Lightness is the subjective appearance of how light a color appears to be. Colorfulness is the degree of difference between a color and gray. Chroma is the colorfulness relative to the brightness of another color that appears white under similar viewing conditions. This allows for the fact that a surface of a given chroma displays increasing colorfulness as the level of illumination increases. Saturation is the colorfulness of a color relative to its own brightness. Hue is the degree to which a stimulus can be described as similar to or different from stimuli that are described as red, green, blue, and yellow, the so-called unique hues. The colors that make up an object’s appearance are best described in terms of lightness and chroma when talking about the colors that make up the object’s surface, and in terms of brightness, saturation and colorfulness when talking about the light that is emitted by or reflected off the object.

SharpGroteskSmBold-21 for logo font - https://www.sharptype.co/typefaces/sharp-grotesk

https://letterboxd.com/settings/data/ -> Add in a help menu

Truncate table letterboxd.movies

## Home page gallery wall created manually using this code
```
const imgs = document.querySelectorAll('.center img');
let offsetX = 0, offsetY = 0, draggingImg = null;
imgs.forEach(img => {

    img.addEventListener('mousedown', (e) => {
        e.preventDefault();
        draggingImg = img;
        const style = img.style;
        positionType = {
            top: style.top !== '',
            left: style.left !== '',
            bottom: style.bottom !== '',
            right: style.right !== ''
        };
        console.log(positionType);

        if (positionType.top) offsetY = e.clientY - img.offsetTop;
        if (positionType.left) offsetX = e.clientX - img.offsetLeft;

        if (positionType.bottom) offsetY = img.parentElement.clientHeight - (e.clientY + img.offsetHeight + parseFloat(style.bottom.slice(0, -2)));
        if (positionType.right) offsetX = img.parentElement.clientWidth - (e.clientX + img.offsetWidth + parseFloat(style.right.slice(0, -2)));

        img.style.cursor = 'grabbing';
    });

    
});

document.addEventListener('mousemove', (e) => {
  if (!draggingImg) return;
  const parent = draggingImg.parentElement;

  if (positionType.top) draggingImg.style.top = `${e.clientY - offsetY}px`;
  if (positionType.left) draggingImg.style.left = `${e.clientX - offsetX}px`;

  if (positionType.bottom) draggingImg.style.bottom = `${parent.clientHeight - (e.clientY + draggingImg.offsetHeight + offsetY)}px`;
  if (positionType.right) draggingImg.style.right = `${parent.clientWidth - (e.clientX + draggingImg.offsetWidth + offsetX)}px`;
});

document.addEventListener('mouseup', () => {
if (draggingImg) {
    draggingImg.style.cursor = 'grab';
    draggingImg = null;
}
});
document.addEventListener('keydown', (e) => {
    if (!draggingImg) return;
    // const step = 10; // Change in size for each keypress
    if (e.key.toLowerCase() === 'w') {
        // Increase size
        // currentWidth += step;
        draggingImg.style.width = `${draggingImg.width + 10}px`;
    } else if (e.key.toLowerCase() === 's') {
        // Decrease size
        // currentWidth = Math.max(step, currentWidth - step); // Prevent size from going below 10px
        draggingImg.style.width = `${draggingImg.width - 10}px`;
    }
});
```

```
let total = [];
const centerRect = document.querySelector('.center').getBoundingClientRect();
document.querySelectorAll('.center img').forEach(img => {
  const imgRect = img.getBoundingClientRect();
  let data = {i: parseInt(img.getAttribute('data-tmdb')), w: img.width};
  if (imgRect.y > centerRect.y + centerRect.height / 2) {
    data['b'] = Math.floor(centerRect.bottom - imgRect.bottom);
  } else {
    data['t'] = Math.floor(imgRect.top - centerRect.top);
  }
  if (imgRect.x > centerRect.x + centerRect.width / 2) {
    data['r'] = Math.floor(centerRect.right - imgRect.right);
  } else {
    data['l'] = Math.floor(imgRect.left - centerRect.left);
  }
  total.push(data);
});
JSON.stringify(total);
```

Examples of weird letterboxd options:
```
getInfo('https://boxd.it/iEEq', 'Free Solo', '2021'));
getInfo('https://boxd.it/aPvo', 'Frozen', '2021');
getInfo('https://boxd.it/2o4Y', 'The Vow', '2012'); // different format of photo
getInfo('https://boxd.it/s1Ym', 'The Queen\'s Gambit', '2020'); // tv show
getInfo('https://boxd.it/yK2u', 'A Sensorial Ride', '2020'); // no picture
getInfo('https://boxd.it/AP3G', 'Emilia Pérez', '2024'); // accent
getInfo('https://letterboxd.com/film/sherlock-the-sign-of-three/', 'Sherlock', '2024'); // has been removed from TMDB
```

## Previous work to cite
https://jamesbvaughan.com/movie-director-genders/
https://github.com/GoodbyteCo/Directed-By-Women
https://womenandhollywood.com/resources/statistics/
https://colorboxd.com/
