# time-shifter
Shifts time codes in subtitles to account for differences in frame rates.

# How to use
1. Open index.php in a text editor and set the values for:
```
FROMFPS // this is the FPS of the source video, example: 29.97
TOFPS // this is the FPS that you want the subtitles to coincide with, example: 25
INPUT_FILE // this is the full path to the subtitle file, example /Users/xavier/Desktop/SomeFunMovie.srt
OUTPUT_FILE // this is the full path to the converted file (the file this script will produce)
```
2. In a command prompt, navigate to the folder that contains the index.php file, example: cd path/to/folder
3. Execute this command: `php -f index.php`

You should now have a new subtitle file with the time codes adjusted to coincide with the desired frame rate.

Good luck!