interface ArtItem {
    id: number;
    media_id: number;
    path: string;
    hash: string;
    type: 'video' | 'image' | 'html';
    duration: number;
    position: number;
}

export default ArtItem;
