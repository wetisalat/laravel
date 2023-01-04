<template>
    <b-card no-body>
        <b-card-body>
            <div class="d-flex justify-content-between flex-wrap">
                <!-- sorting  -->
                <b-col
                    cols="12"
                    md="6"
                    align-sm="right"
                    class="d-flex align-items-right justify-content-start mb-1 mb-md-0"
                >
                    <b-button
                        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                        v-b-modal.modal-location
                        variant="primary"
                    >
                        Add Location
                    </b-button>
                </b-col>
                <!-- modal -->
                <b-modal
                    v-model="show"
                    id="modal-location"
                    hide-footer
                    ok-only
                    ok-title="Location"
                    size="lg"
                    title="Add Location"
                >
                    <location :submitconfirm="submitconfirm" />
                </b-modal>
                <!-- modal -->
                <b-modal
                    v-model="edit_show"
                    ref="edit_show"
                    id="edit_modal-location"
                    hide-footer
                    ok-only
                    ok-title="Location"
                    size="lg"
                    title="Edit Location"
                >
                    <edit-location
                        :edit_data="edit_data"
                        :edit_submitconfirm="edit_submitconfirm"
                    />
                </b-modal>
                <!-- filter -->
                <b-form-group
                    label-cols-sm="2"
                    label-align-sm="left"
                    label-size="md"
                    label-for="filterInput"
                    class="mb-0"
                >
                    <b-input-group>
                        <b-form-input
                            id="filterInput"
                            v-model="filter"
                            type="search"
                            placeholder="Type to Search"
                        />
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">
                                Clear
                            </b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </div>
        </b-card-body>

        <b-table
            hover
            responsive
            borderless
            class="position-relative"
            :items="items"
            :fields="fields"
            :filter="filter"
        >
            <!-- We are using utility class `text-nowrap` to help illustrate horizontal scrolling -->
            <template #head(id)>
                <div class="text-nowrap">ID</div>
            </template>

            <template #head()="scope">
                <div class="text-nowrap">
                    {{ scope.label }}
                </div>
            </template>

            <template #cell(avatar)="data">
                <b-avatar class="mr-1" :src="data.value" />
            </template>

            <template #cell(status)="data">
                <b-badge :variant="status[1][data.value]">
                    {{ status[0][data.value] }}
                </b-badge>
            </template>
            <template #cell(action)="data">
                <div style="display: flex">
                    <span
                        ><i
                            :id="'edit' + data.index"
                            class="bi bi-box-arrow-in-down-left edit"
                            @click="action('edit', data)"
                        ></i></span
                    ><span
                        ><i
                            :id="'delete' + data.index"
                            class="bi bi-trash delete"
                            @click="action('delete', data)"
                        ></i
                    ></span>
                    <span
                        ><i
                            :id="'changeStatus' + data.index"
                            class="bi bi-check2-circle changeStatus"
                            @click="action('changeStatus', data)"
                        ></i
                    ></span>
                </div>
                <!-- <b-tooltip :target="'delete' + data.index" triggers="hover">
                    Delete
                </b-tooltip>
                <b-tooltip :target="'edit' + data.index" triggers="hover">
                    Edit
                </b-tooltip>
                <b-tooltip
                    :target="'changeStatus' + data.index"
                    triggers="hover"
                >
                    Change Status
                </b-tooltip> -->
            </template>
            <template #cell(staff)="data">
                <span class="text-nowrap">
                    <p
                        v-if="data.item.staffArr.length == 0"
                        style="text-align: left"
                    >
                        ---
                    </p>
                </span>
            </template>
        </b-table>
        <b-modal v-model="delete_show" ref="delete" hide-footer title="Delete">
            <div class="d-block text-center">
                <h3>Are you sure?</h3>
            </div>
            <div style="display: flex; justify-content: center">
                <b-button
                    class="mt-3"
                    variant="outline-danger"
                    style="width: 90px"
                    block
                    @click="delete_show = false"
                    >Cancel</b-button
                >
                <b-button
                    class="mt-3 ml-3"
                    variant="primary"
                    style="width: 75px"
                    block
                    @click="toggleModal"
                    >OK</b-button
                >
            </div>
        </b-modal>
    </b-card>
</template>

<script>
import {
    BCard,
    BCol,
    BTable,
    BAvatar,
    BBadge,
    BLink,
    BFormGroup,
    BFormSelect,
    BInputGroup,
    BFormInput,
    BInputGroupAppend,
    BButton,
    BCardText,
    BCardBody,
    BModal,
    VBModal,
    BForm,
    BTooltip,
} from "bootstrap-vue";
import vSelect from "vue-select";
import Ripple from "vue-ripple-directive";
import store from "@/store/index";
import flatPickr from "vue-flatpickr-component";
import Cleave from "vue-cleave-component";
import Location from "./Location.vue";
import EditLocation from "./EditLocation.vue";
// eslint-disable-next-line import/no-extraneous-dependencies
import "cleave.js/dist/addons/cleave-phone.fr";
import axios from "@axios";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BCard,
        BCol,
        BTable,
        BAvatar,
        BBadge,
        BFormGroup,
        BFormSelect,
        Location,
        EditLocation,
        BCardText,
        BInputGroup,
        BFormInput,
        BInputGroupAppend,
        BButton,
        BCardBody,
        flatPickr,
        BTooltip,
        BModal,
        VBModal,
        BForm,
        vSelect,
        Cleave,
    },
    directives: {
        "b-modal": VBModal,
        Ripple,
    },
    data() {
        return {
            selected: "Gender",
            option: ["Male", "Female", "Other"],
            noCollapse: false,
            totalRows: 1,
            currentPage: 1,
            dateDefault: null,
            filter: null,
            delete_id: "",
            delete_show: false,
            show: false,
            edit_data: {},
            edit_show: false,
            fields: [
                {
                    key: "row_id",
                    stickyColumn: true,
                    isRowHeader: true,
                    variant: "primary",
                },
                "Name",
                "Address",
                "Staff",
                { key: "status", label: "Status" },
                { key: "action", label: "Actions" },
            ],
            form: {},
            options: {
                phonenumber: {
                    prefix: "+33",
                    phone: true,
                    blocks: [2, 2, 2, 2, 2],
                },
            },
            /* eslint-disable global-require */
            items: [],
            /* eslint-disable global-require */
            status: [
                {
                    1: "Active",
                    2: "inactive",
                },
                {
                    1: "light-primary",
                    2: "light-danger",
                },
            ],
        };
    },
    methods: {
        getUserId() {
            return JSON.parse(localStorage.getItem("userData")).id;
        },
        loadTable() {
            let id = this.getUserId();
            axios
                .post("api/client/setting-location/getlocation", { id: id })
                .then((res) => {
                    var result = res.data.data;
                    result.map((value, index) => {
                        value.row_id = index + 1;
                        value.Name = value.company_name;
                        value.Address =
                            value.address1 +
                            " " +
                            value.address2 +
                            "," +
                            value.city;
                        value.selectedconvention = JSON.parse(
                            value.selectedconvention
                        );
                        value.selectedcodeAPE = JSON.parse(
                            value.selectedcodeAPE
                        );
                    });
                    this.items = result;
                })
                .catch((err) => {});
        },
        PopUpNotification(msg, type) {
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: msg,
                    icon: "EditIcon",
                    variant: type,
                },
            });
        },
        action(type, value) {
            if (type == "delete") {
                this.delete_id = value.item.id;
                this.$refs["delete"].show();
            } else if (type == "changeStatus") {
                axios
                    .post("api/client/setting-location/changestatuslocation", {
                        id: value.item.id,
                    })
                    .then((res) => {
                        if (res.data.success == true) {
                            this.PopUpNotification(res.data.message, "success");
                            this.loadTable();
                        }
                    });
            } else if (type == "edit") {
                this.$refs["edit_show"].show();
                this.edit_data = value.item;
            }
        },
        toggleModal() {
            axios
                .post("api/client/setting-location/deletelocation", {
                    id: this.delete_id,
                })
                .then((res) => {
                    if (res.data.success == true) {
                        this.PopUpNotification(res.data.message, "success");
                        this.delete_show = false;
                        this.loadTable();
                    }
                });
        },
        submitconfirm() {
            this.loadTable();
            this.show = false;
        },
        edit_submitconfirm() {
            this.loadTable();
            this.edit_show = false;
        },

    },
    computed: {
        sortOptions() {
            // Create an options list from our fields
            return this.fields
                .filter((f) => f.sortable)
                .map((f) => ({ text: f.label, value: f.key }));
        },
    },
    mounted() {
        this.loadTable();
    },
    form: {},
    options: {
        phonenumber: {
            prefix: "+33",
            phone: true,
            blocks: [2, 2, 2, 2, 2],
        },
    },
};
</script>

<style lang="scss">
.edit {
    color: aqua;
    font-size: 20px;
}

.delete {
    margin-left: 13px;
    color: rgb(236, 85, 39);
    font-size: 20px;
}
.changeStatus {
    margin-left: 13px;
    color: green;
    font-size: 20px;
}
.right {
    margin-left: 10px;
}
@import "~@resources/scss/vue/libs/vue-select.scss";
@import "~@resources/scss/vue/libs/vue-flatpicker.scss";
@import "~@resources/scss/vue/libs/vue-wizard.scss";
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css");
</style>
