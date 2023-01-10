<template>
    <div class="board">
        <div class="board__header">
            <span>Add a Board</span>
        </div>
        <div class="board__body">
            <form @submit.prevent="addBoard" class="board__body__form">
                <input v-model="boardTitle" type="text" placeholder="Board Title" required>
                <button type="submit" class="btn m-0">Add</button>
            </form>

            <p v-if="success" class="text-center text-success">{{ success }}</p>
            <p v-if="error" class="text-center text-danger">{{ error }}</p>

            <div v-if="isLoading" class="loading">Adding a board, please wait....</div>
        </div>
    </div>

    <div v-for="board in boards" :key="board.id" class="board">
        <div class="board__header">
            <span>{{ board.title }}</span>
            <button @click="deleteBoard(board.id)" class="btn btn--delete lh-0" title="Delete Board">
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
            </button>
        </div>
        <div class="board__body">
            <card :board="board" />
        </div>
    </div>
</template>

<script>
import Card from './Card.vue';

export default {
    components: {
        "card": Card
    },
    data() {
        return {
            success: false,
            error: false,
            isLoading: false,
            boardTitle: null,
            boards: [],
        }
    },
    methods: {
        getBoards() {
            axios.get('/api/boards')
            .then(res => {
                this.boards = res.data.data;
            }).catch(err => {
                console.log(err)
            })
        },

        addBoard() {
            this.success = false;
            this.error = false;
            this.isLoading = true;

            axios.post('/api/boards', {
                title: this.boardTitle
            }).then(res => {
                this.boards.unshift(res.data.data);
                this.boardTitle = null;

                this.success = res.data.message;
            }).catch(err => {
                console.log(err)

                if (err) {
                    this.error = err.response.data.message;
                }
            }).then(() => {
                this.isLoading = false;

                setTimeout(() => {
                    this.success = false;
                    this.error = false;
                }, 5000);
            })
        },

        deleteBoard(id) {
            if (!confirm("Are you sure, you want to delete this board?")) {
                return false;
            }

            axios.delete(`/api/boards/${id}`)
            .then(res => {
                this.boards = this.boards.filter((e) => e.id !== id);
            }).catch(err => {
                console.log(err)
            }).then(() => {
                //
            })
        }
    },
    mounted() {
        this.getBoards();
    },
}
</script>
