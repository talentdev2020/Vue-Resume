<template>
    <div class='print-paper resume-gold-standard resume-html-page' ref='resume_body'>
       <div class="flex">
            <div class="text-left">
                <p class="resume-title">{{ fullName }}</p>
            </div>
            <div class="text-right full-width-table header-contact-info">
                <div class="contact-info">
                    {{ street_1 }}
                </div>
                <div class="contact-info">
                    {{street_2 }}
                </div>
                <div class="contact-info">
                    {{ city }}{{city ? "," : ""}} {{ province }} {{ zip }}
                </div>
                <div class="contact-info">
                    {{ phone}}
                </div>
                <div class="contact-info">
                    {{ resume.email}}
                </div>
                <div class="contact-info">
                    {{this.resume.linkedin_url}}
                </div>
            </div>
        </div>
 

        <table class="full-width-table resume-section " ref='summary' v-if="resume && resume.resume_summaries[0]" align="center" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td><span class="section-heading " >Summary</span></td>
                </tr>
                <tr>
                    <td class="section-summary">
                        <ul :class="this.resume.resume_summaries[0].bullet_point?'':'bulletless-list'">
                            <li class="summary-list-item" v-for="(summary) in resume.resume_summaries" :key="summary.id" >
                                <p>{{summary.name}}</p>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="full-width-table resume-section" ref='skill'   >
               <div class="section-heading" >Skills</div>
               <div class="skill-body">
                     <div class="skill-row">
                        <div class='skill-column'  v-for="skill in resume.resume_skill" v-bind:key="skill.id">
                            {{ skill.name }}
                        </div>
                    </div>
                 </div>
        </div>

        <div class="full-width-table resume-section" ref='work'  >
       
                <div>
                    <span class="section-heading " ref='work_title'>Work Experience</span> 
                </div>
                <div v-for="work in resume && resume.resume_work_experiences" class="section" :key="work.id" ref="work_child">
                     
                    <table class="full-width-table section-group" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td><span class="section-sub-title">{{ work.position_title }}</span></td>
                            <td class="text-right"><span class="section-dates">{{ dateFormat(work.position_start_date)}} to {{ work.position_end_date ? dateFormat(work.position_end_date) : 'present' }}</span></td>
                        </tr>
                        <tr>
                            <td colspan="2"><span class="section-sub-sub-title">{{ work.position_company }}</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="work-experience-description" v-for="(description, index) in work.resume_descriptions" :key="index">
                                <!-- <ul >
                                    <li class="" v-for="(item, index) in stringToArray(description.description)" :key="index" >
                                        {{item}}
                                    </li>
                                </ul> -->
                                <div class="" v-for="(item, index) in stringToArray(description.description)" :key="index" >
                                    <ul><li> {{item}} </li></ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                 </div>
    
           
        </div>
    
        <div class="full-width-table resume-section" ref='education' >
            <div  v-if="resume && resume.resume_educations[0]">
                <div>
                    <td><span class="section-heading " ref='education_title'>Education</span></td>
                </div>
                <div  v-for="education in resume.resume_educations" :key="education.id" ref='education_child'>
                    <table class="full-width-table section-group " align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td><span class="section-sub-title">{{ education.degree_received }} in {{ education.field_of_study }}</span></td>
                            <td class="text-right"><span class="section-dates">{{ dateFormat(education.start_date) }} to {{ education.end_date ? dateFormat( education.end_date) : 'present' }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="section-sub-sub-title">{{ education.school_name }}</span></td>
                            <td class="text-right"></td>
                        </tr>
                    </table>
                 </div>
            </div>
        </div>
       
        <div class="full-width-table " ref='hobby'  >
                <div><span class="section-heading">Affiliations &amp; Hobbies</span></div>
                <div>
                    <table class="full-width-table" align="center" cellpadding="0" cellspacing="0" v-if="resume.hobbies[0]">
                        <tbody>
                            <tr>
                                <td>
                                    <ul class="">
                                        <li v-for="hobby in resume.hobbies" v-bind:key="hobby.id"><p>{{ hobby.name }}</p></li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>  
    </div>
</template>

<script>
    import {mapState, mapGetters} from 'vuex'
    import {convertDate} from "../../services/utility.js"

    export default {
        data(){
            return {
                pageBottom: 0,
                padding:0,
                scale: 1,
                limit: 100
            }
        },
        computed: {
            ...mapState([
                'phone',
                'street_1',
                'street_2',
                'city',
                'province',
                'zip',
                'country',
                'resume',
            ]),
            ...mapGetters([
                 'fullName',
            ]),

           
        },
        created(){
            window.addEventListener("resize", this.onResize);
        },
        mounted(){
            this.$nextTick(function () {
                this.onResize();
            })
        },
        updated(){
            this.$nextTick(function () {
                this.onResize();
            })
        },
        methods: {
            removePageBreak()
            {
                let breaks = document.getElementsByClassName("html2pdf__page-break");
                let len = breaks.length;
                if(breaks && len > 0)
                {
                    while(breaks[0])
                        breaks[0].remove();
                }

                let headings = document.getElementsByClassName("heading-name");
                len = headings.length;
                if(headings && len > 0)
                {
                    while(headings[0])
                        headings[0].remove(); 
                }

                 let bottoms = document.getElementsByClassName("pagebreak-before-bottom");
                len = bottoms.length;
                if(bottoms && len > 0)
                {
                   let index = len - 1;
                  while(bottoms[index]) {
                       bottoms[index--].classList.remove("pagebreak-before-bottom");

                   }
                }
               
            },
            makePageBreak(){
                this.removePageBreak();
                if(!this.$refs.resume_body){
                    return;
                }
                const start = this.$refs.resume_body.getBoundingClientRect().top;
                const page_bottom = this.pageBottom;
                const offset =  start - this.padding + 20;

                const skill_top = this.$refs.skill.getBoundingClientRect().top
                for(let index = 1; index <= 1 ; index ++)
                {
                    if( (skill_top  < page_bottom * index + offset)  && (skill_top  > page_bottom * (index-1) )){
                        const skill_end = this.$refs.skill.getBoundingClientRect().bottom;

                        if (skill_end > page_bottom * index + offset){
                            this.insertBreak(page_bottom * index, start, skill_top, this.$refs.summary, this.$refs.skill, "div");
                        }
                    }
                }

                if (this.$refs.work_title) {
                    const work_top = this.$refs.skill.getBoundingClientRect().bottom
                    const work_childs = this.$refs.work_child;
                    if ( (work_top  < page_bottom + offset) && work_childs) {
                        let work_first_end = 0;
                        if(Array.isArray(work_childs)){
                            if (work_childs.length > 0 )
                             work_first_end = work_childs[0].getBoundingClientRect().bottom;
                        }else{
                            work_first_end = work_childs.getBoundingClientRect().bottom;
                        }
                        if (work_first_end > page_bottom + offset){
                            if ( (work_first_end - work_top) < this.limit)
                                this.insertBreak(page_bottom, start, work_top, this.$refs.skill, this.$refs.work_title, "div");
                        }
                    }
                
                    if (Array.isArray(work_childs)){
                        this.insertBreakToBlock(page_bottom, start, offset, work_childs, this.$refs.skill)
                    }
                }
                

                 if (this.resume.resume_educations[0] && this.$refs.education_title){
                    const education_top = this.$refs.work.getBoundingClientRect().bottom + 10;
                    const education_childs = this.$refs.education_child;
                    for(let index = 1; index < 3 ; index ++)
                    {
                        if( (education_top  < page_bottom * index + offset)  && (education_top  > page_bottom * (index-1) )){
                            let education_first_end = 0;
                            if(Array.isArray(education_childs) ){
                                if (education_childs.length > 0)
                                education_first_end = education_childs[0].getBoundingClientRect().bottom;
                            }else{
                                education_first_end = education_childs.getBoundingClientRect().bottom;
                            }
                            if (education_first_end > page_bottom * index + offset){
                                this.insertBreak(page_bottom * index, start, education_top, this.$refs.work, this.$refs.education_title, "div");
                            }
                        }
                    }
                
                    if (Array.isArray(education_childs)){
                        this.insertBreakToBlock(page_bottom, start, offset, education_childs, this.$refs.work)
                    }
                }

                const hobby_bottom = this.$refs.hobby.getBoundingClientRect().bottom;
                const education_bottom = this.$refs.education.getBoundingClientRect().bottom;
                for (let index = 1; index <= 3 ; index ++)
                {
                    if( (education_bottom  < page_bottom * index + offset)  && (education_bottom  > page_bottom * (index-1) )) {
                        if (hobby_bottom > page_bottom * index + offset) {
                            this.insertBreak(page_bottom * index, start, education_bottom, this.$refs.education);
                        }
                    }
                }
            },
            onResize(){
                let page_bottom = 0
                if(this.$refs.resume_body && this.$refs.resume_body.offsetWidth > 800)
                {
                    page_bottom = 1056;
                    this.scale = 1;
                    this.padding = 75;
                }
                else if(window.innerWidth >=1500){
                    page_bottom = 890.35;
                    this.padding =63.234;
                    this.scale = 0.84313;
                } else if( window.innerWidth >= 1250){
                    page_bottom = 890.35 * 5 / 6;
                    this.padding =63.234 * 5 / 6;
                    this.scale = 0.84313 * 5 / 6;
                }else{
                    page_bottom = 890.35 * 2 / 3;
                    this.padding =63.234 * 2 / 3;
                    this.scale = 0.84313 * 2 / 3;
                }
               
                // if(this.pageBottom !== page_bottom)
                    this.pageBottom = page_bottom
                    this.makePageBreak();
            },
            insertBreakToBlock(page_bottom, start, offset, childs, beforeNode){
              let flag  = 0;
                if(Array.isArray(childs)) {
                    childs.map( ( element, index ) => {
                        {
                            let top = element.getBoundingClientRect().top;
                            if(index !==0) {
                                top = childs[index - 1].getBoundingClientRect().bottom;
                            }

                            let bottom = element.getBoundingClientRect().bottom;
                            let child_details = element.getElementsByTagName("div");
                            
                            if (child_details.length > 0) {
                                const title_bottom = child_details[0].getBoundingClientRect().bottom;
                                if ( ( top < page_bottom + start) && (title_bottom > page_bottom + offset) )
                                {
                                    if (index ===0)
                                        this.insertBreak(page_bottom, start, top, beforeNode);
                                    else 
                                        this.insertBreak(page_bottom, start, top, childs[index - 1] );
                                    flag = 1;   
                                }
                                else if ( ( top < page_bottom * 2 + start ) && (title_bottom > page_bottom * 2 + offset ) ){
                                    if (index ===0)
                                        this.insertBreak(page_bottom * 2, start, top, beforeNode );
                                    else
                                        this.insertBreak(page_bottom * 2, start, top, childs[index - 1] );
                                    
                                    flag = 1;
                                }
                            }
                            
                            if(flag === 0) {
                                if(index !==0) {
                                    top = childs[index - 1].getBoundingClientRect().bottom;
                                }
                                top = element.getBoundingClientRect().top;
                                bottom = element.getBoundingClientRect().bottom;
                                child_details = element.getElementsByTagName("div");
                                if ( (bottom - top) < this.limit  || (child_details && child_details.length===1)  || !child_details ) {
                                    if ( ( top < page_bottom + start) && (bottom > page_bottom + offset) )
                                    {
                                        if( index ===0 )
                                            this.insertBreak(page_bottom, start, top, beforeNode );
                                        else
                                            this.insertBreak(page_bottom, start, top, childs[index - 1] );
                                    }
                                    else if ( ( top < page_bottom * 2 + start ) && (bottom > page_bottom * 2 + offset ) ){
                                        if( index ===0 )
                                            this.insertBreak(page_bottom, start, top, beforeNode );
                                        else 
                                            this.insertBreak(page_bottom * 2, start, top, childs[index - 1] );
                                    }
                                } else {
                                this.insertBreakToChildBlock(page_bottom, start, offset, child_details); 
                                }
                            }
                           
                        }
                        return element;
                    })
                }
            },
            insertBreakToChildBlock(page_bottom, start, offset, childs){
                const len = childs.length;
                let flag = 0;
                for(let i = 1; i < len; i ++) {
                    if(flag !== 0)
                        break;
                    let element = childs[i];
                    {
                        const top = childs[i-1].getBoundingClientRect().bottom;
                        const bottom = element.getBoundingClientRect().bottom;
                        if ( ( top < page_bottom + start) && (bottom > page_bottom + offset) )
                        {
                            this.insertBreak(page_bottom, start, top, childs[i - 1], element );
                            flag = 1;
                        }
                        else if ( ( top < page_bottom * 2 + start ) && (bottom > page_bottom * 2 + offset ) ){
                            this.insertBreak(page_bottom * 2, start, top, childs[i - 1], element );
                            flag = 1;
                        }
                    }
                }
            },
            stringToArray: function (str) {
                if (str && str.length > 0)
                    return str.split(/\r?\n/)
                return [];
            },

            insertAfter(new_node, existing_node) {
                existing_node.parentNode.insertBefore(new_node, existing_node.nextSibling);
            },

            insertBreak(page_bottom, page_start, current_top , current_node, next_node){
                const margin_top = (page_bottom - current_top + page_start) * this.scale;
                let new_node = document.createElement("div");
                new_node.classList.add("html2pdf__page-break")
                new_node.setAttribute("style" ,`margin-top: ${margin_top}px`);
                current_node.classList.add("pagebreak-before-bottom");
                this.insertAfter(new_node, current_node);

                let name_element = document.createElement("div");
                name_element.innerHTML = this.fullName;
                name_element.classList.add("heading-name")
                name_element.classList.add("text-right")
                name_element.classList.add("heading-bold")
                this.insertAfter(name_element, new_node);
                // next_node.classList.add("page-top");
            },
            dateFormat(date){
                return convertDate(date, this.resume.date_format);
            },
        }
    }
</script>
<style scoped>

</style>