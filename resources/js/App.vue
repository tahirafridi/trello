<template>
    <div class="grid">
        <board />
    </div>
</template>

<script>
import Board from './components/Board.vue';

export default {
    components: {
        "board": Board,
    }
}
</script>
