<template>
    <vue-autosuggest
        v-model="option"
        :suggestions="filteredOptions"
        :input-props="inputProps"
        @selected="onSelected"
        :limit="3"
        @input="onInputChange"
    >
        <template slot-scope="{ suggestion }">
            <div class="d-flex align-items-center">
                <b-avatar :src="suggestion.item.flag" />
                <div class="detail ml-50">
                    <b-card-text class="mb-0">
                        {{ suggestion.item.name }}
                    </b-card-text>
                    <small class="text-muted">
                        {{ suggestion.item.code }}
                    </small>
                </div>
            </div>
        </template>
    </vue-autosuggest>
</template>

<script>
import { VueAutosuggest } from "vue-autosuggest";
import { BCard, BAvatar, BCardText } from "bootstrap-vue";

export default {
    components: {
        VueAutosuggest,
        BCard,
        BCardText,
        BAvatar,
    },
    data() {
        return {
            datasuggest: [],
            filteredOptions: [],
            inputProps: {
                id: "autosuggest__input",
                class: "form-control",
                placeholder: "France",
            },
            limit: 3,
            selected: null,
        };
    },
    props: {
        option: "",
    },
    created() {
        this.$http.get("/autosuggest/data").then((res) => {
            this.datasuggest = res;
        });
    },
    methods: {
        onSelected(option) {
            console.log(option, "this is calliung");
            this.selected = option.item;
        },
        onInputChange(text) {
            if (text === "" || text === undefined) {
                return;
            }

            /* Full control over filtering. Maybe fetch from API?! Up to you!!! */
            const filteredCountries = this.datasuggest.data[0].countries
                .filter(
                    (item) =>
                        item.name.toLowerCase().indexOf(text.toLowerCase()) > -1
                )
                .slice(0, this.limit);
            const filteredData = filteredCountries;
            this.filteredOptions = [
                {
                    data: filteredData,
                },
            ];
        },
    },
};
</script>
