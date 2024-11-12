<template>
    <h1>This is the contact page</h1>
    <Link href="/about">About page</Link><br/>
    <Link href="/">Welcome</Link>
</template>

<script>
import {Link} from "@inertiajs/vue3";
export default {
    name: 'Contact',
    components: {Link}
}
</script>
