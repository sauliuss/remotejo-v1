<template>
    <ul role="navigation" class="pagination">
      <li class="page-item" :class="pages.current_page <= 1 ? 'disabled' : ''">
        <button @click.prevent="$emit('submit', pages.current_page - 1)" class="page-link">
            <icon :name="'arrow--left'"></icon>
        </button>
      </li>

      <li class="page-item" v-for="n in shownNumbers" :class="n == pages.current_page ? 'active' : ''">
        <a v-if="n > 0" href="#" @click.prevent="$emit('submit', n)" class="page-link">{{ n }}</a>
        <span v-else>. . .</span>
      </li>

      <li class="page-item" :class="pages.current_page >= pages.last_page ? 'disabled' : ''">
        <a href="#" @click.prevent="$emit('submit', pages.current_page + 1)" class="page-link">
            <icon :name="'arrow--right'"></icon>
        </a>
      </li>
    </ul>
</template>
<script>
        export default {
            name: 'pagination',
            data() {
                return {

                    
                }
            },
            computed: {

                shownNumbers: function (){
                    let current_page = this.pages.current_page;
                    let total_pages = this.pages.last_page;
                    // How many pages in total should be shown
                    let visible_pages_count = this.visible_pages;

                    // Decides if the pagination is needed
                    if(visible_pages_count < total_pages){
                        const visible_pages_array =  [];

                        // When the current page is in the beginning
                        if(current_page < visible_pages_count){                            

                            for(let i = 1; i <= visible_pages_count; i++){
                                visible_pages_array.push(i);
                            }

                            // Adds the seperator "..." and the last page to the end
                            visible_pages_array.push(0, total_pages);
                        }

                        // When the current page is in the end
                        else if(current_page > total_pages - visible_pages_count + 1){
                            for(let i = total_pages - visible_pages_count + 1; i <= total_pages; i++){
                                visible_pages_array.push(i);
                            }

                            // Adds the first page and the seperator "..." to the start
                            visible_pages_array.unshift(1, 0);
                        }

                         // When the current page is in the middle
                        else{
                            for (let i = current_page - 1; i <= current_page + 1; i++){
                                visible_pages_array.push(i);
                            }

                            // Adds Page 1 and the seperator "..." to the beginning
                            visible_pages_array.unshift(1, 0);
                            // Adds the last page and the seperator "..." to the end
                            visible_pages_array.push(0, total_pages);
                        }

                        return visible_pages_array;

                    }
                    // If a total page number is less than visible_pages, return a full aray of page numbers
                    else{
                        return this.pages.last_page;
                    }
                }

            },
            props: {
                pages: {
                    type: Object,
                    required: true
                },
                visible_pages: {
                    type: Number,
                    default: 5,
                    required: false
                }

            }
        }
</script>
