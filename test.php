<?php
include './glyphit.php';

$text = <<< EOB
<p>Thanks to 7-8 people cat-food or foo - bar a <em><strong>break</strong></em> in the schedules of Continuum's musicians -- actually, to achieve this fortuitous gap one musician had to take off a week of TSO work, another had to request time from the opera, and two simply couldn't make it and had to sub out of the runout   ... nevertheless, by whatever means, a window opened--- we went to Montreal last month to perform at <a title="Continuum @ MNM" href="http://www.festivalmnm.ca/fr/2011/prog/concert/28144/" target="_blank">Montreal Nouvelles Musiques</a>, SMCQ's biennial festival. The concert was in the Conservatoire's new building in a hall designed, it would seem, more for string quartet than ensemble with percussion, and Laurent Philippe on the piano - but it was fantastic anyway. The audience was a respectable size for a festival with overlapping events and the response was enthusiastic. Of course, afterwards we hit a great bistro and drank and ate until having to get some sleep before the drive (or flight, or various combinations of the two) back, the guerilla musical offensive on Montreal concluded for the time being. <a title="Continuum's MNM program" href="http://www.continuummusic.org/pdf/program-montreal-feb-22.pdf" target="_blank">Read the program</a> from the Montreal concert online.</p>
EOB;

$g = new GlyphIt;

$new = $g->fix($text);

echo $new;
echo "\n\n";
