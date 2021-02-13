<template>
    <div>
        <div v-for="(link, index) in links" class="row">
            <input type="hidden" :name="`${collectionName}[${index}][id]`" v-model="link.id" />
            <div class="col-md-5">
                <div class="form-group">
                    <label :for="`${collectionName}_${index}_url`">URL</label>
                    <input :id="`${collectionName}_${index}_url`" type="text" :name="`${collectionName}[${index}][url]`" v-model="link.url" class="form-control form_link" placeholder="http://">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label :for="`${collectionName}_${index}_label`">Zobrazená adresa</label>
                    <input :id="`${collectionName}_${index}_label`" type="text" :name="`${collectionName}[${index}][label]`" v-model="link.label" class="form-control form_link" placeholder="wikipédia">
                </div>
            </div>
            <div class="col-md-2 text-right">
              <div class="form-group">
                <label class="force-block">&nbsp;</label> <!-- spacer -->
                <a class="btn btn-danger btn-outline" @click.prevent="links.splice(index, 1)"><i class="fa-times fa"></i> zmazať</a>
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <a class="btn btn-info btn-outline" @click.prevent="links.push({})"><i class="fa fa-plus"></i> pridať</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            collectionName: {
                type: String,
                required: true,
            },
            value: Array
        },
        data() {
            return {
               links: this.value || [{}]
            }
        },
    }
</script>
