<template>
    <b-card>
        <!-- media -->
        <b-media no-body>
            <b-media-aside>
                <b-link>
                    <b-img
                        ref="previewEl"
                        rounded
                        v-if="optionsLocal.avatar!==''"
                        :src="optionsLocal.avatar"
                        height="80"
                    />
                    <b-img
                        ref="previewEl"
                        rounded
                        v-else
                        src="storage/images/avatar/user_empty.png"
                        height="80"
                    />
                </b-link>
                <!--/ avatar -->
            </b-media-aside>

            <b-media-body class="mt-75 ml-75">
                <!-- upload button -->
                <b-button
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    variant="primary"
                    size="sm"
                    class="mb-75 mr-75"
                    @click="$refs.refInputEl.$el.click()"
                >
                    Upload
                </b-button>
                <b-form-file
                    ref="refInputEl"
                    v-model="profileFile"
                    accept=".jpg, .png, .gif"
                    :hidden="true"
                    plain
                    @change="inputImageRenderer"
                />
                <!--/ upload button -->

                <!-- reset -->
                <b-button
                    v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                    variant="outline-secondary"
                    size="sm"
                    class="mb-75 mr-75"
                    @click.prevent="resetImage"
                >
                    Reset
                </b-button>
                <!--/ reset -->
                <b-card-text
                    >Allowed JPG, GIF or PNG. Max size of 800kB</b-card-text
                >
            </b-media-body>
        </b-media>
        <!--/ media -->

        <!-- form -->
        <b-form class="mt-2">
            <b-row>
                <b-col sm="4">
                    <b-form-group label="Company" label-for="account-company">
                        <b-form-input
                            v-model="optionsLocal.company"
                            name="company"
                            placeholder="Company name"
                        />
                    </b-form-group>
                </b-col>

                <b-col sm="4">
                    <b-form-group label="SIRET" label-for="siret">
                        <b-form-input
                            v-model="optionsLocal.siret"
                            name="siret"
                            placeholder="SIRET"
                        />
                    </b-form-group>
                </b-col>

                <b-col sm="4">
                    <b-form-group label="TVA Intracom" label-for="tva">
                        <b-form-input
                            v-model="optionsLocal.tva_intracom"
                            name="tva"
                            placeholder="TVA Intracom"
                        />
                    </b-form-group>
                </b-col>

                <b-col sm="6">
                    <b-form-group label="E-mail" label-for="account-e-mail">
                        <b-form-input
                            v-model="optionsLocal.email"
                            name="email"
                            placeholder="Email"
                        />
                    </b-form-group>
                </b-col>

                <b-col sm="6">
                    <b-form-group label="Phone Number" label-for="phonenumber">
                        <cleave
                            id="phonenumber"
                            v-model="form.block"
                            class="form-control"
                            :raw="false"
                            :options="options.phonenumber"
                            placeholder="+33 6 12 34 56 78"
                        />
                    </b-form-group>
                </b-col>

                <b-col sm="6">
                    <b-form-group label="First Name" label-for="firstname">
                        <b-form-input
                            v-model="optionsLocal.first_name"
                            placeholder="First Name"
                            name="firstname"
                        />
                    </b-form-group>
                </b-col>

                <b-col sm="6">
                    <b-form-group label="Last Name" label-for="lastname">
                        <b-form-input
                            v-model="optionsLocal.last_name"
                            name="lastname"
                            placeholder="Last Name"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12">
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        variant="primary"
                        class="mt-2 mr-1"
                        @click.prevent="SaveChange"
                    >
                        Save changes
                    </b-button>
                    <b-button
                        v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                        variant="outline-secondary"
                        type="reset"
                        class="mt-2"
                        @click.prevent="reset"
                    >
                        Reset
                    </b-button>
                </b-col>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
import {
    BFormFile,
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import { ref } from "@vue/composition-api";
import Cleave from "vue-cleave-component";
// eslint-disable-next-line import/no-extraneous-dependencies
import "cleave.js/dist/addons/cleave-phone.fr";
import axios from "@axios";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BButton,
        BForm,
        BImg,
        BFormFile,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BMedia,
        BMediaAside,
        BMediaBody,
        BLink,
        Cleave,
    },
    directives: {
        Ripple,
    },
    props: {
        generalData: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            optionsLocal: {},
            profileFile: null,
            form: {},
            options: {
                phonenumber: {
                    prefix: "+33",
                    phone: true,
                    blocks: [2, 2, 2, 2, 2],
                },
                number: {
                    blocks: [3, 3, 3, 4],
                    phone: true,
                },
            },
        };
    },
    methods: {
        getUserId() {
            return JSON.parse(localStorage.getItem("userData")).id;
        },
        resetImage() {
            let user_id = this.getUserId();
            this.optionsLocal.avatar = "";
            // axios
            //     .post("api/client/account-setting/data", { id: user_id })
            //     .then((res) => {
            //         let { avatar } = res.data.data;
            //         if (avatar == null) {
            //             avatar = "/images/avatar/user_empty.png";
            //         }
            //         this.optionsLocal.avatar = avatar;
            //     });
        },
        reset(){
            this.optionsLocal = {
                        email: "",
                        phone_number: "",
                        last_name: "",
                        first_name: "",
                        company: "",
                        siret: "",
                        tva_intracom: "",
                        avatar: this.optionsLocal.avatar,
                    };
                    this.form.block = "";
        },
        togetGereralData() {
            let user_id = this.getUserId();
            axios
                .post("api/client/account-setting/data", { id: user_id })
                .then((res) => {
                    let {
                        email,
                        phone,
                        siret,
                        company,
                        tva_intracom,
                        firstname,
                        lastname,
                        avatar,
                    } = res.data.data;
                    if (avatar == null) {
                        avatar = "";
                    }
                    this.optionsLocal = {
                        email: email,
                        phone_number: phone,
                        last_name: lastname,
                        first_name: firstname,
                        company: company,
                        siret: siret,
                        tva_intracom: tva_intracom,
                        avatar: avatar,
                    };
                    this.form.block = phone;
                });
        },
        SaveChange() {
            let user_id = this.getUserId();
            this.optionsLocal.id = user_id;
            this.optionsLocal.phone_number = this.form.block;
            axios
                .post("api/client/account-setting/save_change", this.optionsLocal)
                .then((res) => {
                    if (res.data.success == true) {
                        this.PopUpNotification(res.data.message, "success");
                        this.togetGereralData();
                    }
                })
                .catch((err) => {
                    this.PopUpNotification(err.response.data.message, "error");
                });
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
        async inputImageRenderer(e) {
            let image = await new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    resolve(e.target.result);
                };
                reader.readAsDataURL(e.target.files[0]);
            });
            axios
                .post("api/client/account-setting/download_image", {
                    image: image,
                })
                .then((res) => {
                    this.optionsLocal.avatar = res.data.data;
                });
        },
    },
    setup() {
    },
    mounted() {
        this.togetGereralData();
    },
};
</script>
