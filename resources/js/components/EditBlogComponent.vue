<template>
  <div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Blog</h1>
    </div>
    <div class="row">
      <!-- Pie Chart -->
      <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Edit Blog</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors" :key="error">{{error}}</li>
                        </ul>
                    </div>
                </div>
            </div>
              <div class="form-group row">
                <div class="col-sm-12 mb-3 mb-sm-0">
                  <label>Main Image</label><br>
                  <img :src="'/../storage/'+new_update_blog.main_image" class="img-responsive" style="max-height:350px; margin-bottom:10px" v-if="new_update_blog.main_image!=''"><br>
                  <template v-if="updated_image!=''">
                    <label>Updated Image</label><br>
                    <img :src="updated_image" class="img-responsive" style="max-height:350px; margin-bottom:10px" v-if="new_update_blog.main_image!=''">
                  </template>
                  <input type="file" class="form-control form-control-user" placeholder="Main Image" @change="onUpdateMainImageChange" id="update_main_image_upload" accept="image/*">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-12 mb-3 mb-sm-0">
                  <label>Title</label>
                  <input type="text" class="form-control form-control-user"  placeholder="Title" v-model="new_update_blog.title">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-12">
                  <label>Description</label>
                  <editor v-model="new_update_blog.description"

                    api-key="gssljk5uv5x6t2kif8srfvvuzj9g22oqase8m28dqkqrr5e4"
                    :init="{
                      height: 300,
                      menubar: false,
                      mode : 'textareas',
                      force_br_newlines : false,
                      force_p_newlines : false,
                      forced_root_block : '',
                      entity_encoding: 'raw',
                      relative_urls : false,
                      remove_script_host : false,
                      convert_urls : false,
                      plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                      ],
                      toolbar:
                        'formatselect | bold italic backcolor | \
                        alignleft aligncenter alignright alignjustify | \
                        bullist numlist outdent indent | removeformat | image'
                    }"
                  />
                </div>
              </div>
            <div class="form-group row">
              <div class="col-sm-12">
                <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" @click="updateBlog()"><i class="fa-sm text-white-50"></i>Edit Blog</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
    import Editor from '@tinymce/tinymce-vue'
    export default {
        props: ['blogId'],
        data(){
            return{
                errors: [],
                new_update_blog:[],
                updated_image:'',
                uri:'/api/resource/blog/' + this.blogId,
            }
        },
        components:{
          'editor':Editor
        },
        methods:{
            loadBlog(){
                axios.get(this.uri).then(response=>{
                  this.errors="";
                  this.new_update_blog = response.data.blog
                });
            },
            onUpdateMainImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createUpdateMainImage(files[0]);
            },
            createUpdateMainImage(file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.updated_image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            updateBlog(){
                let formData = new FormData();
                var update_main_image_file = document.querySelector('#update_main_image_upload');
                formData.append("_method", 'PATCH');
                formData.append('title',this.new_update_blog.title);
                formData.append('description',this.new_update_blog.description);
                let main_image = ''
                if (update_main_image_file.files[0]) {
                  main_image = update_main_image_file.files[0]
                }
                formData.append('main_image',main_image);
                
                axios.post(this.uri,formData,{
                  headers: {
                    'Content-Type': 'multipart/form-data'
                  }
                }).then(response=>{
                        this.resetData();
                        $("#update-modal").modal("hide");
                        toastr.success(response.data.status);
                        window.location.href = "/admin/blog";
                }).catch(error=>{
                    if (error.response.data.name){
                        this.errors.push(error.response.data.name[0]);
                    }
                });
            },
            resetData(){
                this.errors=[];
                var update_main_image_file = document.querySelector('#update_main_image_upload');
                update_main_image_file.value='';
                this.new_update_blog.main_image = '';
            },
        },
        mounted() {
            this.loadBlog();
            this.loadFiles();
            console.log('Blogs mounted.')
        }
    }
</script>