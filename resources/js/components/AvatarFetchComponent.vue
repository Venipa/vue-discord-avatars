<template>
    <div
        class="d-flex flex-grow-1 flex-column justify-content-center align-items-center"
    >
        <div class="container">
            <div class="mb-3 text-center">
                <h1>Discord Avatar</h1>
                <h6>Fetch Discord Avatars by their User ID</h6>
            </div>
            <template v-if="avatar">
                <div
                    class="d-flex flex-column justify-content-center align-items-center mb-3"
                >
                    <div class="user-avatar shadow-sm">
                        <img
                            :src="avatar.url"
                            class="img-fluid"
                            alt="avatar"
                        />
                    </div>
                </div>
            </template>
            <template v-else>
                <div
                    class="d-flex flex-column justify-content-center align-items-center mb-3"
                >
                    <div class="user-avatar user-avatar-filler shadow-sm">
                        <span>None selected</span>
                    </div>
                </div>
            </template>
            <b-form-group :state="!status$" :invalid-feedback="status$">
                <b-form-input
                    type="text"
                    @change="updateAvatar"
                    :state="status && !status$"
                    :placeholder="`UserId Example: 457593547844091905`"
                    v-model="uid"
                    trim
                    debounce="800"
                ></b-form-input>
            </b-form-group>
            <template v-if="lastUids && lastUids.length > 0">
                <b-list-group>
                    <b-list-group-item
                        button
                        :disabled="isLoading"
                        class="pointer-event"
                        v-for="lastUid in lastUids"
                        :class="{ active: uid === lastUid }"
                        @click="() => selectUID(lastUid)"
                        :key="lastUid"
                        >{{ lastUid }}</b-list-group-item
                    >
                </b-list-group>
            </template>
            <div
                class="mt-4 d-flex flex-row align-items-center justify-content-center"
            >
                <b-link
                    href="https://github.com/Venipa"
                    class="mr-2"
                    target="_blank"
                    >Github</b-link
                >
            </div>
        </div>
    </div>
</template>

<script>
function setQueryStringParameter(name, value, replace = false) {
    const params = new URLSearchParams(window.location.search);
    params.set(name, value);
    (replace ? window.history.replaceState : window.history.pushState)({}, "", decodeURIComponent(`${window.location.pathname}?${params}`));
}
const defaultData = () => ({
    uid: null,
    avatar: null,
    status: null,
    lastUids: null,
    isLoading: false
});
import { axios } from "../bootstrap";
import { uniq } from "lodash";
export default {
    data() {
        return defaultData();
    },
    computed: {
        status$() {
            if (this.uid && !/^[0-9]*$/.test(this.uid)) {
                return "Invalid UserID";
            }
            const status = this.status;
            return status && status.type === "danger" ? status.text : null;
        }
    },
    props: {
        initialId: String
    },
    methods: {
        selectUID(uid) {
            if (uid === this.uid) {
                return;
            }
            this.uid = uid;
            this.updateAvatar();
        },

        updateAvatar() {
            const uid = this.uid;

            if (!/^[0-9]*$/.test(uid)) {
                return;
            }
            this.isLoading = true;
            axios
                .get("/api/v1/user/avatar", {
                    params: {
                        uid
                    }
                })
                .then(x => {
                    this.avatar = x.data;
                    this.status = null;
                    let uIdx;
                    if (!this.lastUids) {
                        this.lastUids = [uid];
                        this.isLoading = false;
                        return;
                    }
                    if ((uIdx = this.lastUids?.indexOf(uid)) !== -1) {
                        if (uIdx === 0) {
                            this.isLoading = false;
                            return;
                        }
                        this.lastUids = uniq([
                            uid,
                            ...this.lastUids.slice(0, 4)
                        ]);
                    } else {
                        this.lastUids = uniq([
                            uid,
                            ...this.lastUids.slice(0, 4)
                        ]);
                    }
                    localStorage.lastUids = JSON.stringify(this.lastUids);
                    this.isLoading = false;
                    setQueryStringParameter('id', uid);
                })
                .catch(err => {
                    const errors = err?.response?.data?.errors;
                    this.isLoading = false;
                    if (errors) {
                        this.status = {
                            type: "danger",
                            text: errors[Object.keys(errors)[0]][0]
                        };
                    }
                });
        }
    },
    mounted() {
        this.lastUids = JSON.parse(localStorage.lastUids || "[]");
        if (this.initialId) this.selectUID(this.initialId);
    }
};
</script>
<style lang="scss" scoped>
.display-avatar {
    height: 140px;
    width: 140px;
}
.user-avatar {
    height: 200px;
    background-color: #020202;
    border-radius: 8px;
    overflow: hidden;
    > img {
        height: 100%;
    }
    &-filler {
        width: 200px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
}
</style>
