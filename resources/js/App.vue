<template>
    <div class="grid">
        <board />
    </div>

    <modal name="my-first-modal">
        This is my first modal
    </modal>
</template>

<script>
import Board from './components/Board.vue';

export default {
    components: {
        "board": Board,
    }
}
</script>
