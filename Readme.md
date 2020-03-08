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

You can test the microservice from outside the container:

    curl -X POST -H "Content-Type: application/json" --data "@test.json" localhost:8094/convert | jq .

Or make the test from inside the container:

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
        "logs": []
    }

# Credits

This repo was inspired by

* https://github.com/GGracieux/lilypond-api
* https://github.com/mikechernev/midi2mp3-docker

# Author

weesee@web.de

(C) Copyright, 2020




    
