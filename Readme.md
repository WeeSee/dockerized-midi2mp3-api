Midi2Mp3
========

REST API for converting MIDI files to MP3 audio files

# Setup and run

You need to have installed:

* docker
* docker-compose
* make

## Running the container

Run the container using the prebuild image from Dockerhub:

```bash
docker run -p 8094:80 midi2mp3
```

## Building the container 

You can build the image and start it up with ```make``` and ```docker-compose```

    $ make build up ps

There are more commands in the Makefile, try

    $ make

## API Usage

### Endpoint http://[docker-machine]/info

#### Request
- Verb : GET
- No parameter
	
#### Response
- Content-Type : Application/json
```json
{
  "apiName": "midi2mp3",
  "version": {
    "api": "1.0"
  },
  "description": "midi to mp3 audio files cnverter"
}
```  
	
### Endpoint http://[docker-machine]/convert
	
#### Request	
- Verb : POST
- Content-Type : Application/json
- Parameters :
-- midiData : Base64 encoded MIDI-Data (see test section for an example)
	
#### Response
- Content-Type : Application/json
```json  
{
  "statusCode": "OK|ERROR",
  "message": "Information on error",
  "base64MidiData": "....",
  "logs": []
}
```


# Test from command line

Or make the test from inside the container:

    $ make bash

and build a request file:

    # echo -n "{\"midiData\":\"" > /tmp/test.json
    # cat /tmp/elton.mid | base64 --wrap=0 >> /tmp/test.json
    # echo "\"}" >> /tmp/test.json

or as a one liner:

    # { echo -n "{\"midiData\":\""; echo -n "`base64 --wrap=0 /tmp/elton.mid`"; echo "\"}"; } > /tmp/test.json

then execute the conversion 

    # curl -X POST -H "Content-Type: application/json" --data "@/tmp/test.json" localhost/convert | jq .

You should get a json output containing the base64 encoded mp3 file as

    {
        "statusCode": "OK",
        "message": "",
        "base64Mp3Data": ".......",
        "logs": []
    }

You can test the microservice from outside the container (this is more complicated since you need the tools installed in your os):

    curl -X POST -H "Content-Type: application/json" --data "@test.json" localhost:8094/convert | jq .


# Credits

This repo was inspired by

* https://github.com/GGracieux/lilypond-api
* https://github.com/mikechernev/midi2mp3-docker

# Author

weesee@web.de

(C) Copyright, 2020




    
