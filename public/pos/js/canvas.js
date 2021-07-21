// Global Vars
let width = window.innerWidth,
  height = 0,
  filter = "none",
  streaming = false;

// DOM Elements
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
const constraints = {
    advanced: [{
        facingMode: "environment"
    }]
};
// Get media stream
navigator.mediaDevices
  .getUserMedia({   video: constraints, audio: false })
  .then(function (stream) {
    // Link to the video source
    video.srcObject = stream;
    // Play video
    video.play();
  })
  .catch(function (err) {
    console.log(`Error: ${err}`);
  });

// Play when ready
video.addEventListener(
  "canplay",
  function (e) {
    console.log("222222");

    if (!streaming) {
      console.log("111111");
      // Set video / canvas height
      height = video.videoHeight / (video.videoWidth / width);
      video.setAttribute("width", width);
      video.setAttribute("height", height);
      canvas.setAttribute("width", width);
      canvas.setAttribute("height", height);

      streaming = true;
    }
  },
  false
);

video.addEventListener("play", () => {
  function step() {
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    requestAnimationFrame(step);
    javascriptBarcodeReader({
      /* Image ID || HTML5 Image || HTML5 Canvas || HTML5 Canvas ImageData || Image URL */
      image: canvas,
      barcode: "code-2of5",
      // barcodeType: 'industrial',
      options: {
        // useAdaptiveThreshold: true // for images with sahded portions
        // singlePass: true
      },
    })
      .then((code) => {
          if(code.length>0){
              console.log(code);
              alert(code);
          }
      })
      .catch((err) => {
        console.log(err);
      });
  }
  requestAnimationFrame(step);
});
