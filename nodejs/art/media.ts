import MediaType from "./media-type.enum";

/**
 * La clase representa a un arte multimedia.
 */
class Media {
    /**
     * @param {number} id Id de la columna `media_id` de la tabla `arts`.
     * @param {number} artId Id de la columna `id` de la tabla `arts`.
     * @param {MediaType} type Tipo de arte.
     * @param {string} path Ruta de la media.
     * @param {string} hash Hash de la media.
     * @param {number} duration Duración de la media en segundos (default: 10).
     * @param {number} position Posición de la media en el carousel.
     */
    constructor(
        public readonly id: number,
        public readonly artId: number,
        public readonly type: MediaType,
        public readonly path: string,
        public readonly hash: string,
        public readonly duration: number,
        public readonly position: number
    ) {
        if (this.duration <= 0) {
            this.duration = 10;
        }
    }
}

export default Media;
