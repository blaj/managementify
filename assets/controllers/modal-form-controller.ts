import { Controller } from '@hotwired/stimulus';
import { Modal } from 'mdb-ui-kit';
import { FrameElement, TurboBeforeFetchResponseEvent, visit } from '@hotwired/turbo';

export default class extends Controller<HTMLElement> {
  static targets = ['modal', 'frame'];

  static values = {
    formUrl: String
  };

  declare readonly modalTarget: HTMLDivElement;
  declare readonly frameTarget: FrameElement;

  modal: Modal = null;

  connect = (): void => {
    document.addEventListener('turbo:before-fetch-response', this.beforeFetchResponse);
  };

  disconnect = (): void => {
    document.removeEventListener('turbo:before-fetch-response', this.beforeFetchResponse);
  };

  openModal = async ({ params: { src } }: { params: { src?: string } }): Promise<void> => {
    if (src !== null) {
      this.frameTarget.src = src;
    }

    if (!this.modal) {
      this.modal = new Modal(this.modalTarget);
    } else {
      await this.frameTarget.reload();
    }

    this.modal.show();
  };

  private beforeFetchResponse = (event: TurboBeforeFetchResponseEvent): void => {
    if (!this.modal || !this.modal._isShown) {
      return;
    }

    const fetchResponse = event.detail.fetchResponse;

    if (fetchResponse.succeeded && fetchResponse.redirected) {
      event.preventDefault();
      this.modal.hide();
      visit(fetchResponse.location);
    }
  };
}
