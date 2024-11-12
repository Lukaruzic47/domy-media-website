<template>
    <div>
      <Head title="about"/>
        <h1>About Page</h1>
        <p>This is the About Page!</p>
        <Link href="/">Go to Home</Link><br/>
        <Link href="/contact">Go to Contact</Link>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";

export default {
    name: 'About',
    components: { Link, Head },
};
</script>
