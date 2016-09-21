(function (global) {
    /* Wrapper function to make things easier */
    var AudioAnalyser = function (audio, passthru) {
        return new Promise((resolve, reject) => {
            (new _AudioAnalyser(audio, passthru))
                .then((analyser) => resolve(analyser))
                .catch((error) => reject(error));
        });
    }
    /* Constructor, will return a promise. */
    function _AudioAnalyser (audio, options) {

        this.audioContext = null;
        this.analyserNodes = [];
        this.channelSplitter = null;
        this.sourceNode = null;
        this.audioElement = null;
        this.passthru = (options && 'passthru' in options) ? options.passthru : false;
        this.mono = (options && 'mono' in options) ? options.mono : true;
        this.smoothing = (options && 'smoothing' in options) ? options.smoothing : 0;
        this.rmsValues = [];
        this.channelCount = -1;

        Object.defineProperty(this, 'audio', {
            get: function() {
                return this.audioElement;
            },
            set: undefined
        });

        Object.defineProperty(this, 'channels', {
            get: function() {
                return this.channelCount;
            },
            set: undefined
        });

        if (this.passthru) {
            this.channelMerger = null;
        }

        /* initalize web audio api objects */
        try {
            this.audioContext = window.AudioContext || window.webkitAudioContext;
            if (!this.audioContext) {
                return Promise.reject('No AudioContext found.');
            }
            this.audioContext = new this.audioContext();
        } catch (e) {
            return Promise.reject(e);
        }


        /* main promise return from constructor. */
        return new Promise((resolve, reject) => {
            if (audio === 'microphone') {
                if (!navigator) {
                    reject('userMedia can only be used in browser environments'); return;
                } else {

                    navigator.mediaDevices.getUserMedia({
                        audio: true, video: false
                    }).then(((stream) => {
                        this.sourceNode = this.audioContext.createMediaStreamSource(stream);
                        this.createGraph();
                        resolve(this); return;
                    }).bind(this)).catch((error) => {
                        reject(error); return;
                    });
                }
            } else if (typeof audio === 'string') { /* URL input */
                try {

                    var createCORSRequest = function(method, url) {
                        var xhr = new XMLHttpRequest();
                        if ("withCredentials" in xhr) {
                            // Most browsers.
                            xhr.open(method, url, true);
                        } else if (typeof XDomainRequest != "undefined") {
                            // IE8 & IE9
                            xhr = new XDomainRequest();
                            xhr.open(method, url);
                        } else {
                            // CORS not supported.
                            xhr = null;
                        }
                        return xhr;
                    };

                    var url = audio;
                    var method = 'GET';
                    var xhr = createCORSRequest(method, url);

                    var stateChangeCorsCheck = function(xhr) {
                        if (xhr.readyState == XMLHttpRequest.HEADERS_RECEIVED && xhr.status == 200) {
                            xhr.onreadystatechange = undefined;
                            xhr.onprogress = undefined;
                            this.audioElement = new Audio(audio);
                            this.audioElement.crossOrigin = 'anonymous';

                            this.sourceNode = this.audioContext.createMediaElementSource(this.audioElement);
                            this.createGraph();
                            xhr.abort();
                            resolve(this); return;
                        }
                    };

                    xhr.onreadystatechange = stateChangeCorsCheck.bind(this, xhr);

                    xhr.onerror = (function() {
                        console.log("CORS NOT ENABLED");
                        reject('Could not load URL. CORS not enabled.'); return;
                    }).bind(this);
                    window.xhr = xhr;
                    xhr.send();

                } catch (e) {
                    reject(e); return;
                }
            } else if (audio instanceof Audio || audio instanceof HTMLAudioElement) { /* HTML5 Audio Element input */
                try {
                    this.audioElement = audio;
                    this.sourceNode = this.audioContext.createMediaElementSource(this.audioElement);
                    this.createGraph();
                    resolve(this); return;
                } catch (e) {
                    reject(e); return;
                }

            } else if (audio instanceof File) {
                var audioURL = URL.createObjectURL(audio);
                this.audioElement = new Audio(audioURL);
                this.sourceNode = this.audioContext.createMediaElementSource(this.audioElement);
                this.createGraph();
                resolve(this); return;
            } else {
                reject("No suitable handler for audio input"); return;
            }
        });
    }

    /* sets up audio api graph, channel splitters/mergers if non-mono, passthru to destination. */
    _AudioAnalyser.prototype.createGraph = function () {
        if (!this.mono) {

            (new Promise((resolve, reject) => {
                /* determine how many channels there are */
                var xhr = new XMLHttpRequest();
                xhr.open('GET', this.audioElement.src, true);
                xhr.responseType = "arraybuffer";
                xhr.onload = (function() {
                  this.audioContext.decodeAudioData(xhr.response, function(decodedBuffer) {
                    resolve(decodedBuffer.numberOfChannels);
                  });
                }).bind(this);
                xhr.error = function () {
                    reject("Unable to retrieve channels.");
                }
                xhr.send(null);
            })).then((channels) => {

                this.channelCount = channels;
                this.channelSplitter = this.audioContext.createChannelSplitter(channels);

                if (this.passthru) {
                    this.channelMerger = this.audioContext.createChannelMerger(channels);
                }

                this.sourceNode.connect(this.channelSplitter);

                for (var i = 0; i < channels; i++) {
                    this.analyserNodes.push(
                        (this.audioContext.createAnalyser())
                    );
                    this.analyserNodes[i].smoothingTimeConstant = this.smoothing;
                    this.channelSplitter.connect(this.analyserNodes[i], i, 0);
                    if (this.passthru) {
                        this.analyserNodes[i].connect(this.channelMerger, 0, i);
                    }
                }
                if (this.passthru) {
                    this.channelMerger.connect(this.audioContext.destination);
                }
            });

        } else if (this.mono) {
            this.analyserNodes[0] = this.audioContext.createAnalyser();
            this.analyserNodes[0].smoothingTimeConstant = this.smoothing;
            this.sourceNode.connect(this.analyserNodes[0]);
            if (this.passthru) {
                this.analyserNodes[0].connect(this.audioContext.destination);
            }
        }
    }

    _AudioAnalyser.prototype.start = function() {
        return new Promise((resolve, reject) => {
            try {
                if (this.audio) { /* audio element source */
                    this.audio.addEventListener('canplay', function(event) {
                        this.audio.play();
                        resolve(true); return;
                    });
                } else {
                    resolve(true); return;
                }
            } catch (e) {
                reject(e); return;
            }
        });
    }



    _AudioAnalyser.prototype.waveform = function(channel) {
        if (!channel) channel = 0;
        if (!this.analyserNodes[channel]) {
            throw 'Cannot obtain waveform data for channel ' + channel + ', no analyser found.';
        }
        var data = new Uint8Array(this.analyserNodes[channel].frequencyBinCount);
        this.analyserNodes[channel].getByteTimeDomainData(data);
        return data;
    };

    _AudioAnalyser.prototype.frequencies = function(channel) {
        if (!channel) channel = 0;
        if (!this.analyserNodes[channel]) {
            throw 'Cannot obtain frequency data for channel ' + channel + ', no analyser found.';
        }
        var data = new Uint8Array(this.analyserNodes[channel].frequencyBinCount);
        this.analyserNodes[channel].getByteFrequencyData(data);
        return data;
    };

    _AudioAnalyser.prototype.rms = function(channel) {
        if (!channel) channel = 0;
        var data = this.waveform(channel);
        var total = 0;
        for (var i = 0; i < data.length; i++) {
            total += Math.pow(Math.abs(data[i] - 128), 2);
        }
        return Math.sqrt(total / data.length);

    }

    _AudioAnalyser.prototype.end = function() {
        this.audioContext.close();
        delete this;
    }



    global.AudioAnalyser = AudioAnalyser;
})(this);

