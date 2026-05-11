import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Mousewheel } from 'swiper/modules';
import 'swiper/css';

interface UltimosGanhos {
    id: number;
    namewin: string;
    prizename: string;
    valueprize: number;
    imgprize: string;
}

interface AoVivoProps {
    ultimosGanhos?: UltimosGanhos[];
    isLoading?: boolean;
}

function AoVivoSkeleton() {
    return (
        <div className="flex">
            <div className="w-24 h-14.5 bg-muted-foreground/15 rounded border" />
            <div className="flex overflow-hidden gap-2 ml-2 flex-1 min-w-0">
                {Array.from({ length: 8 }).map((_, i) => (
                    <div key={i} className="w-55.5 h-14.5 bg-muted-foreground/15 rounded border" />
                ))}
            </div>
        </div>
    );
}

export default function AoVivo({ ultimosGanhos, isLoading = false }: AoVivoProps) {
    const data = ultimosGanhos || [];
    const fallback = [
        { id: 1, namewin: "João S***", prizename: "Raspadinha Premium", valueprize: 25, imgprize: "https://ik.imagekit.io/azx3nlpdu/Notas/25%20REAIS.png?updatedAt=1752047821875" },
        { id: 2, namewin: "Maria A***", prizename: "Super Prêmio", valueprize: 50, imgprize: "https://ik.imagekit.io/azx3nlpdu/Notas/50%20REAIS.png?updatedAt=1752047821875" },
        { id: 3, namewin: "Pedro L***", prizename: "Mega Sorte", valueprize: 100, imgprize: "https://ik.imagekit.io/azx3nlpdu/Notas/100%20REAIS.png?updatedAt=1752047821875" },
        { id: 4, namewin: "Ana C***", prizename: "Jackpot", valueprize: 500, imgprize: "https://ik.imagekit.io/azx3nlpdu/Notas/500%20REAIS.png?updatedAt=1752047821875" },
        { id: 5, namewin: "Carlos M***", prizename: "Prêmio Especial", valueprize: 10, imgprize: "https://ik.imagekit.io/azx3nlpdu/Notas/10%20REAIS.png?updatedAt=1752047821875" },
    ];
    const items = data.length > 0 ? data : fallback;
    const formatCurrency = (value: number) => value.toLocaleString("pt-BR", { minimumFractionDigits: 2 });

    if (isLoading) return <AoVivoSkeleton />;

    return (
        <div className="flex mb-1 sm:mb-8">
            <Swiper
                modules={[Autoplay, Mousewheel]}
                loop={true}
                autoplay={{ delay: 700, disableOnInteraction: false, stopOnLastSlide: false }}
                mousewheel={{ forceToAxis: true }}
                slidesPerView="auto"
                spaceBetween={12}
                pagination={false}
                className="flex overflow-hidden gap-2 ml-2 list-shadow"
            >
                {items.map((item) => (
                    <SwiperSlide
                        key={item.id}
                        className="!flex items-center justify-center gap-3 py-3 px-7 select-none group rounded-lg !w-46 cursor-pointer hover:bg-secondary border bg-card/50 transition-colors"
                    >
                        <img src={`/storage${item.imgprize}`} className="size-8 object-contain" alt={item.namewin} />
                        <div className="flex flex-col text-xs sm:text-sm min-w-0">
                            <span className="font-medium text-xs text-amber-400/75 overflow-hidden text-nowrap text-ellipsis">{item.namewin}</span>
                            <h1 className="font-medium text-xs text-muted-foreground overflow-hidden text-nowrap text-ellipsis">{item.prizename}</h1>
                            <span className="font-semibold text-foreground">
                                <span className="text-emerald-300">R$ </span>
                                {formatCurrency(Number(item.valueprize))}
                            </span>
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>
        </div>
    );
}