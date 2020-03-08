Midi2Mp3
========

# Setup and run

You need to have installed:

* docker
* docker-compose
* make

Then you can build the image and start it up

    $ make build up ps


# Test

Go inside container:

    $ make bash

and build a request file:

    # echo -n "{\"midiData\":\"" > /tmp/test.json
    # cat /tmp/elton.mid | base64 --wrap=0 >> /tmp/test.json
    # echo "\"}" >> /tmp/test.json

or as one liner:

    # { echo -n "{\"midiData\":\""; echo -n "`base64 --wrap=0 /tmp/elton.mid`"; echo "\"}"; } > /tmp/test.json

then execute the conversion

    # curl -X POST -H "Content-Type: application/json" --data "@/tmp/test.json" localhost/convert | jq .

You should get a json output containing the base64 encoded mp3 file as

    {
        "statusCode": "OK",
        "message": "",
        "base64Mp3Data": "...",
    "logs": [
        {
            "title": "Midi->Mp3 conversion",
            "content": "20200308-130050: start conversion\n20200308-130050: cmd = [timidity /tmp/converter-data/5e64ec82b1274/5e64ec82b1274.mid -Ow -o - | ffmpeg -i - -acodec libmp3lame -ab 64k /tmp/converter-data/5e64ec82b1274/output.mp3]\n"
        }
    ]
    }



Or make a test from outside the container:

    curl -X POST -H "Content-Type: application/json" --data "@test.json" localhost:8094/convert | jq .



    
