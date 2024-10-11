import ArtItem from "./art-item.interface";
import ArtType from "./art-type.enum";
import Media from "./media";
import sortBy from 'lodash/sortBy';
import MediaType from "./media-type.enum";

/**
 * Arte multimedia.
 */
class Art {
    /**
     * Duración total del arte en base a la duración de sus medias.
     */
    private duration: number = 0;

    /**
     * Lista de medias.
     */
    private mediaList: Media[] = [];

    /**
     * Indice actual de la media en el carousel.
     * Las artes de tipo carousel solo pueden mostrar una media a la vez, respetando el orden de las mismas.
     * Cuando se muestra una media, se incrementa en 1 la posición actual, y se reinicia a 0 cuando se llega al final.
     */
    private carouselIndex: number = 0;

    /**
     *
     * @param {number} id
     * @param {number} mediaId
     * @param {ArtType} type
     * @param {Item[]} items
     */
    constructor(
        public id: number,
        public mediaId: number,
        public type: ArtType,
        items: ArtItem[]
    ) {
        this.id = id;
        this.mediaId = mediaId;
        this.type = type;

        this.handleItems(items);
    }

    /**
     * Retorna el conjunto de medias a mostrar.
     * En caso de ser un carousel, solo se mostrará una media a la vez por cada llamada a este método.
     */
    public getMedias(): Media[] {
        if (this.type === 'carousel') {
            return this.getCarouselMedias();
        }

        return this.mediaList;
    }

    private getCarouselMedias(): Media[] {
        const media = this.mediaList[this.carouselIndex];

        this.carouselIndex += 1;

        if (this.carouselIndex >= this.mediaList.length) {
            this.carouselIndex = 0;
        }

        // La duración de un carousel depende de la duración de la media actual.
        this.duration = media.duration;

        return [media];
    }

    /**
     * Maneja el registro de las medias.
     */
    private handleItems(items: ArtItem[]): void {
        for (let item of this.sortItems(items)) {
            const media = this.addMediaToList(item);

            this.duration += media.duration;
        }

        // Si el arte es de tipo carousel, la duración es la de la primera media.
        if (this.type === ArtType.CAROUSEL) {
            this.duration = this.mediaList[this.carouselIndex].duration;
        }
    }

    /**
     * Agrega una media a la lista de medias.
     */
    private addMediaToList(item: ArtItem): Media {
        const { id: artId, media_id: mediaId, type, path, hash, duration, position } = item;

        const media = new Media(mediaId, artId, type as MediaType, path, hash, duration, position);

        this.mediaList.push(media);

        return media;
    }

    /**
     * Ordena los items por posición.
     */
    private sortItems(items: ArtItem[]): ArtItem[] {
        return sortBy(items, 'position');
    }
}

export default Art;
