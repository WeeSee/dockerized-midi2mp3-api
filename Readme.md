Midi2Mp3
========

![Docker Cloud Build Status](https://img.shields.io/docker/cloud/build/weesee/midi2mp3-api) ![Docker Image Size (latest by date)](https://img.shields.io/docker/image-size/weesee/midi2mp3-api) ![Docker Pulls](https://img.shields.io/docker/pulls/weesee/midi2mp3-api) ![GitHub issues](https://img.shields.io/github/issues/weesee/dockerized-midi2mp3-api) 

REST API for converting MIDI files to MP3 audio files.

Upload a JSON with base64 encoded Midi data and get a JSON including the base64 encoded Mp3 file. All details see below.

This is a stateless microservice without volumes. The microservice runs forever and accepts http requests on the specified port. 

# Setup and run

You need to have installed:

* docker
* docker-compose
* make

## Running the container

Run the container using [the prebuild image from Dockerhub](https://hub.docker.com/r/weesee/midi2mp3-api):

```bash
docker run -p 8094:80 midi2mp3
```

## Building the container 

You can build the image and start it up with ```make``` and ```docker-compose```

    make build up ps

There are more commands in the Makefile, try

    make

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
```bash
    make bash
```

and build a request file:
```bash
    echo -n "{\"midiData\":\"" > /tmp/test.json
    cat /tmp/elton.mid | base64 --wrap=0 >> /tmp/test.json
    echo "\"}" >> /tmp/test.json
```

or as a one liner:
```bash
    { echo -n "{\"midiData\":\""; echo -n "`base64 --wrap=0 /tmp/elton.mid`"; echo "\"}"; } > /tmp/test.json
```

then execute the conversion 
```bash
    curl -X POST -H "Content-Type: application/json" --data "@/tmp/test.json" localhost/convert | jq .
```

You should get a json output containing the base64 encoded mp3 file as
```bash
    {
        "statusCode": "OK",
        "message": "",
        "base64Mp3Data": ".......",
        "logs": []
    }
```

You can test the microservice from outside the container (this is more complicated since you need the tools installed in your os):
```bash
    curl -X POST -H "Content-Type: application/json" --data "@test.json" localhost:8094/convert | jq .
```

To extract the mp3 file out of the result on the command line, use
```bash
    curl -X POST -H "Content-Type: application/json" --data "@/tmp/test.json" localhost/convert | jq -r .base64Mp3Data | base64 -d > /tmp/elton.mp3
```

# Credits

This repo was inspired by

* https://github.com/GGracieux/lilypond-api
* https://github.com/mikechernev/midi2mp3-docker

# Author

weesee@web.de

(C) Copyright, 2020




    
