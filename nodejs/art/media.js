
/**
 * @typedef { 'image' | 'video' | 'html' } MediaType
 */
class Media {
    /**
     * valor de la columna `media_id` de la tabla art.
     *
     * @type {number}
     */
    id;

    /**
     * valor de la columna `id` de la tabla art.
     *
     * @type {number}
     */
    artId;

    /**
     * tipo de media.
     *
     * @type {MediaType}
     */
    type;

    /**
     * Path del archivo.
     *
     * @type {string}
     */
    path;

    /**
     * Hash del archivo
     *
     * @type {string}
     */
    hash;

    /**
     * Duración del archivo.
     *
     * @type {number}
     */
    duration = 10;

    /**
     * Posición del archivo.
     *
     * @type {number}
     */
    position = 0;

    /**
     * @param {number} id
     * @param {number} artId
     * @param { 'image'|'video'|'html' } type
     * @param {string} path
     * @param {string} hash
     * @param {number} duration
     * @param {number} position
     */
    constructor(id, artId, type, path, hash, duration, position) {
        this.id = id;
        this.artId = artId;
        this.type = type;
        this.path = path;
        this.hash = hash;
        this.duration = duration === 0 ? 10 : duration;
        this.position = position;
    }
}

module.exports = Media;
