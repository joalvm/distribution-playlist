const Media = require("./media");
const sortBy = require('lodash/sortBy')

/**
 * @typedef {{id:number, media_id:number, type:string, path:string, hash:string, duration:number, position:number}} Item
 * @typedef { 'image' | 'video' | 'html' | 'carousel' | 'sequence' } ArtType
 */
class Art {
    /**
     * valor de la columna `id` de la tabla art.
     *
     * @type {number}
     */
    id;

    /**
     * valor de la columna `media_id` de la tabla art.
     *
     * @type {number}
     */
    mediaId;

    /**
     * tipo de arte.
     *
     * @type {ArtType}
     */
    type;

    /**
     * Duración del arte.
     *
     * @type {number}
     */
    duration = 0;

    /**
     * @type {Media[]}
     */
    #mediaList = [];

    /**
     * Pasición actual de la media en el carousel.
     * Las artes de tipo carousel solo pueden mostrar una media a la vez, respetando el orden de las mismas.
     * Cuando se muestra una media, se incrementa en 1 la posición actual, y se reinicia a 0 cuando se llega al final.
     */
    #currentCarouselPosition = 0;

    /**
     *
     * @param {number} id
     * @param {number} mediaId
     * @param {ArtType} type
     * @param {Item[]} items
     */
    constructor(id, mediaId, type, items) {
        this.id = id;
        this.mediaId = mediaId;
        this.type = type;

        this.#handleItems(items);
    }

    /**
     * Retorna el conjunto de medias a mostrar.
     * En caso de ser un carousel, solo se mostrará una media a la vez por cada llamada a este método.
     *
     * @returns {Media[]}
     */
    getMedias() {
        if (this.type === 'carousel') {
            const currentMedia = this.#mediaList[this.#currentCarouselPosition];

            this.#currentCarouselPosition += 1;

            if (this.#currentCarouselPosition >= this.#mediaList.length) {
                this.#currentCarouselPosition = 0;
            }

            // La duración de un carousel depende de la duración de la media actual.
            this.duration = currentMedia.duration;

            return [currentMedia];
        }

        // La duración de un sequence o un arte video|image|html es la suma de las duraciones de sus items.
        this.duration = this.#mediaList.reduce((acc, media) => acc + media.duration, 0);

        return this.#mediaList;
    }

    /**
     * Maneja el registro de las medias.
     *
     * @param {Item[]} items
     */
    #handleItems(items) {
        for (let item of this.#sortItems(items)) {
            const { id: artId, media_id: mediaId, type, path, hash, duration, position } = item;

            const media = new Media(mediaId, artId, type, path, hash, duration, position);

            this.#mediaList.push(media);

            this.duration += media.duration;
        }

        // Si el arte es de tipo carousel, la duración es la de la primera media.
        if (this.type === 'carousel') {
            this.duration = this.#mediaList[this.#currentCarouselPosition].duration;
        }
    }

    /**
     * Ordena los items por posición.
     *
     * @param {Item[]} items
     * @returns {Item[]}
     */
    #sortItems(items) {
        return sortBy(items, 'position');
    }
}

module.exports = Art;
