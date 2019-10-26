<template>

  <div class="uploader-container">
      <div class="uploader" ref="fileform">
          <div v-if="image_preview" class="btn-remove-upload" @click.prevent="removeImage()">
            <icon class="icon-close icon-close--dark" :name="'close--normal'"></icon>
          </div>
          <div class="uploader__preview" :style="{ backgroundImage: 'url(' + image_preview + ')' }">
              <label :for="'input-file'+company_id" class="uploader__title" v-if="!image_preview">
                <span class="uploader__label"> Drag and drop a file</br> or click here</span>
                <input type="file" ref="fileinput" :id="'input-file'+company_id" name="input-file" class="uploader__input" accept="image/*" v-on:change="selectImage()">
              </label>
          </div>
      </div>           
  </div>
</template>

<script>
export default {
  name: 'uploader',
  data () {
    return {
      drag_and_drop: false,
      files: [],
      image_preview: this.uploaded_image 
    }
  },
  computed: {
  },
  props: {
    company_id: Number,
    uploaded_image: String,
  },
  mounted: function () {
    /*
    ** Test if browser supports drag and drop functionality
    */
    this.drag_and_drop = this.testDragAndDrop();

    if(this.drag_and_drop){
      this.dropImage();
    }
  },
  methods: {
    testDragAndDrop() {

      /*
      ** Create a div to test if browser supports
      ** drag and drop functionality
      */
      let div = document.createElement('div');

      /*
      ** Check if 'draggable', 'ondragstart' and 'ondrop' are available.
      ** Also check if FormData and FileReader objects exist,
      ** so we could upload and display files.
      */
      return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window
    },
    addDraggingClass(el) {
      el.classList.add('is-dragging');
    },
    removeDraggingClass(el) {
      el.classList.remove('is-dragging');
    },
    dropImage() {
      let uploader = this.$refs.fileform;
      /**
      ** Iterate over all drag-related events and bind each event listener on the form
      ** which we access with a global $refs array
      **/
      ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach(function(evt) {
        uploader.addEventListener(evt, function(e){

        /**
        ** Prevent the default action (i.e. opening files in the browser)
        ** Stop propagation so that no other element could open files.
        **/
          e.preventDefault();
          e.stopPropagation();
        }.bind(this), false);
      }.bind(this));


      /**
      ** Iterate over all drag-start-related events and add
      ** 'is-dragging' class to each event.
      **/
      ['drag', 'dragstart','dragenter', 'dragover'].forEach(function(evt) {
        uploader.addEventListener(evt, function(e){
          this.addDraggingClass(uploader);

        }.bind(this), false);
      }.bind(this));

      /**
      ** Iterate over all drag-start-related events and remove
      ** 'is-dragging' class.
      **/
      ['dragleave', 'dragend', 'drop'].forEach(function(evt) {
        uploader.addEventListener(evt, function(e){
          this.removeDraggingClass(uploader);

        }.bind(this), false);
      }.bind(this));


      /**
      ** Add an event listener for 'drop' to the form
      **/
      uploader.addEventListener('drop', function(e){

        /*
        ** Capture the files from the 'drop' event and add them to a local files array
        */
        for(let i = 0; i < e.dataTransfer.files.length; i++){
          /*
          ** Emit 'upload' event to a parent's component
          */
          this.files.push(e.dataTransfer.files[i]);
          this.$emit('upload', e.dataTransfer.files[i]);
          this.getImagePreview();
        }

      }.bind(this));
    },
    selectImage() {
      let file_input = this.$refs.fileinput;

      for(let i = 0; i < file_input.files.length; i++){
        this.files.push(file_input.files[0]);
        this.$emit('upload', file_input.files[0]);
        this.getImagePreview();   
      }

      file_input.files = null;
    },
    removeImage() {
      this.files = [];
      this.image_preview = '';
      this.$emit('remove');
    },
    getImagePreview() {
      /*
      ** Set an array index to 0. So we could select only the first file from files array
      ** (if multiple file are uploaded)
      */
      let i = 0;

      /*
      ** Check if a file is an image
      */
      if (/\.(jpe?g|png|svg)$/i.test( this.files[i].name )){

        /*
        ** Create a new FileReader object
        */
        let reader = new FileReader();

        /*
        ** Add an event listener when the file has been loaded
        */
        reader.addEventListener('load', function(){
          /*
          ** Remove previous image
          */
          this.image_preview = reader.result;
        }.bind(this),false);

        /*
        ** Read the data for the file in through the reader. When it has
        ** been loaded, we listen to the event propagated and set the image
        ** src to what was loaded from the reader.
        */
        reader.readAsDataURL(this.files[i]);
      }
    }
  }
}
</script>
