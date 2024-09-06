# moon-image-icons
Moon image icons derived from NASA moon photographs

## Metodology

This distribution contains images from NASA for a full month of views at 3 hour intervals which are rendered at the MP4 file at https://svs.gsfc.nasa.gov/4310/

Credit: NASA's Scientific Visualization Studio, Visualizer: Ernie Wright (USRA)

It was downloaded as media1.mp4, then processed by

   https://mp4-to-gif.utils.com/

to create an animated GIF, then the animated GIF was processed by

  https://ezgif.com/split

to extract the individual 1920x1080 frames as separate images.

The images were renamed from 'frame_nnn_delay-m.mms.gif' to 'moon_nnn.gif'
where 'nnn' is the frame number and saved in the ./NASA-moons/ directory.

Per NASA docs:
-  This short looping animation (MP4) shows a complete cycle of lunar phases. 
-  The view is geocentric, lunar north up. 
-  The frames are at intervals of 3 hours, with a total length of 236 frames representing a synodic month of 29.5 days. 
-  To find the frame number corresponding to a particular age in days, use the formula

  frame number = ( days × 8 ) + 1

Then using that formula, days=0..29 images were selected and reprocessed by PHP GD to produce the ,/moonimg/
50x50 transparent gif images (gen-moon-images.php)

Note: the Southern Hemisphere images were created by rotating the Northern Hemisphere images by 180 degrees.

## Sample images
Icon: ./moonimg/NH-moon14.gif

![./moonimg/NH-moon14](https://github.com/user-attachments/assets/8fbaa410-09c2-403a-af6a-bcf0e11541e7)

Icon: ./moonimg-lg/NH-moon14.gif
![./moonimg-lg/NH-moon14](https://github.com/user-attachments/assets/1620a111-35dc-4fae-bbf6-92f06e9615da)

Source image: ./NASA-moons/moon_125.gif
![./NASA-moons/moon_125](https://github.com/user-attachments/assets/cddc6c1c-0287-4b02-b1fe-04c48cd95f66)




