<template>
    <Head></Head>

    <div class="p-0 m-0 lg:p-10 xl:px-15 xl:py-10">
        <div class="flex">
           <section class="w-full lg:w-180 lg:h-100 bg-gray-300 rounded-xl mt-5">
                <div class="overflow-hidden rounded-xl">
                    <video
                        controls
                        ref="video"
                        class="w-full roundex-xl"
                    >
                    </video>
                </div>

           </section>
        </div>

        <section
            class="mt-10 w-full bg-linear-to-tr from-white/30 to-white/40 dark:bg-linear-to-br dark:from-gray-950/20 dark:to-gray-950/40 shadow-lg dark:shadow p-5 rounded-lg"
        >
            <div>
                <BaseHeading class="text-lg"> Death Note - Episode 1</BaseHeading>
            </div>
        </section>


    </div>

</template>

<script>
import Hls from 'hls.js';
export default {
    props: {
        'episodeData': Object
    },
    data() {

    },
    mounted() {
        this.playEpisode();
    },
    methods: {
        playEpisode() {
            const video = this.$refs.video;
            const proxyBase = 'https://anidb-proxy.seaanime.workers.dev/?';

            const defaultLoader = Hls.DefaultConfig.loader;
            const referer = this.episodeData.refererUrl;

            class ProxyLoader extends defaultLoader {
                load(context, config, callbacks) {
                if (!context.url.includes('anidb-proxy')) {
                    context.url = proxyBase + 'url=' + encodeURIComponent(context.url) + '&ref=' + encodeURIComponent(referer);
                }
                super.load(context, config, callbacks);
                }
            }

            const hls = new Hls({ enableWorker: false, loader: ProxyLoader });
            hls.loadSource(this.episodeData.episodeUrl);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, () => video.play());

        }
    }
}
</script>
