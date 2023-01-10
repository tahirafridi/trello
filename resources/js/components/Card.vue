<template>
    <div class="card">
        <div v-if="cards.length" v-for="card in cards" :key="card.id" class="card__header">
            <span>{{ card.title }}</span>
            <a @click="deleteCard(card.id)" href="javascript:void(0);" title="Delete Card">
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
            </a>
        </div>
        <p v-else class="text-center text-muted">No card found.</p>

        <div class="card__body">
            <form @submit.prevent="addCard" class="board__body__form board__body__form--vertical">
                <input v-model="card.title" type="text" placeholder="Card Title" required>
                <textarea v-model="card.description" rows="3" placeholder="Card Description"></textarea>
                <button type="submit" class="btn">Add</button>

                <p v-if="success" class="text-center text-success">{{ success }}</p>
                <p v-if="error" class="text-center text-danger">{{ error }}</p>
            </form>

            <div v-if="isLoading" class="loading">Adding a board, please wait....</div>
        </div>
    </div>
</template>

<script>
import CardModal from './CardModal.vue'

export default {
    props: {
        board: Object
    },
    data() {
        return {
            error: false,
            isLoading: false,
            card: {
                title: null,
                description: null
            },
            cards: [],
        }
    },
    methods: {
        getCards() {
            axios.get(`/api/boards/${this.board.id}/cards`)
            .then(res => {
                this.cards = res.data.data;
            }).catch(err => {
                console.log(err)
            })
        },

        addCard() {
            this.success = false;
            this.error = false;
            this.isLoading = true;

            axios.post(`/api/boards/${this.board.id}/cards`, this.card).then(res => {
                this.cards.unshift(res.data.data);

                this.card.title = null;
                this.card.description = null;

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

        deleteCard(id) {
            if (!confirm("Are you sure, you want to delete this card?")) {
                return false;
            }

            axios.delete(`/api/boards/${this.board.id}/cards/${id}`)
            .then(res => {
                this.cards = this.cards.filter((e) => e.id !== id);
            }).catch(err => {
                console.log(err)
            }).then(() => {
                //
            })
        }
    },
    mounted() {
        this.getCards(this.boardId);
    }, 
}
</script>