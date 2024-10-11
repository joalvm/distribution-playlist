import ArtItem from "./art-item.interface";

interface ArtData {
    id: number;
    media_id: number;
    type: 'carousel' | 'sequence' | 'video' | 'image' | 'html';
    medias: ArtItem[];
}

export default ArtData;
