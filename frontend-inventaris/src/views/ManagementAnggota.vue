<template>
  <div class="container-fluid p-4">
    <!-- Header with Search -->
    <div class="row align-items-center mb-4">
      <div class="col-md-6">
        <h2 class="mb-0">Management Anggota</h2>
      </div>
      <div class="col-md-6">
        <div class="d-flex gap-2 justify-content-end">
          <div class="flex-grow-1" style="max-width: 300px; position: relative">
            <i
              class="bi bi-search"
              style="
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #999;
              "
            ></i>
            <input
              type="text"
              class="form-control form-control-sm search-input"
              placeholder="Search Anggota"
              v-model="searchQuery"
              style="padding-left: 36px"
            />
          </div>
          <button class="btn btn-dark btn-sm" @click="openAddModal">
            <i class="bi bi-plus"></i> Tambah Anggota
          </button>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive bg-white rounded">
      <table class="table table-hover mb-0">
        <thead>
          <tr class="table-header">
            <th>No</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Department</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in filteredAnggota" :key="item.id">
            <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
            <td>{{ item.nama }}</td>
            <td>{{ item.jabatan }}</td>
            <td>{{ item.department }}</td>
            <td class="text-center">
              <div class="dropdown">
                <button
                  class="btn btn-sm action-btn"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu action-dropdown">
                  <li>
                    <a
                      href="#"
                      @click.prevent="editItem(item)"
                      class="action-item"
                      >Edit</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      @click.prevent="deleteItem(item.id)"
                      class="action-item"
                      >Delete</a
                    >
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
      <div class="d-flex align-items-center gap-2">
        <span class="text-muted">Showing</span>
        <select
          v-model.number="itemsPerPage"
          class="form-select form-select-sm"
          style="width: auto; display: inline-block"
        >
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="15">15</option>
          <option value="20">20</option>
        </select>
        <span class="text-muted">
          of <strong>{{ filteredAnggota.length }}</strong>
        </span>
      </div>
      <nav>
        <ul class="pagination mb-0">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <a
              class="page-link pagination-link"
              href="#"
              @click.prevent="previousPage"
              >Previous</a
            >
          </li>
          <li
            v-for="page in totalPages"
            :key="page"
            class="page-item"
            :class="{ active: page === currentPage }"
          >
            <a
              class="page-link pagination-link"
              href="#"
              @click.prevent="goToPage(page)"
              >{{ page }}</a
            >
          </li>
          <li
            class="page-item"
            :class="{ disabled: currentPage === totalPages }"
          >
            <a
              class="page-link pagination-link"
              href="#"
              @click.prevent="nextPage"
              >Next</a
            >
          </li>
        </ul>
      </nav>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            style="position: absolute; top: 1rem; right: 1rem"
          ></button>
          <div class="modal-body p-4">
            <h5 class="mb-3 fw-600">
              {{ isEditing ? "Edit Data" : "Tambah Data" }}
            </h5>
            <form>
              <div class="mb-3">
                <label class="form-label fw-600 mb-2">Nama</label>
                <input
                  type="text"
                  class="form-control form-control-custom"
                  v-model="newItem.nama"
                />
              </div>
              <div class="mb-3">
                <label class="form-label fw-600 mb-2">Jabatan</label>
                <input
                  type="text"
                  class="form-control form-control-custom"
                  v-model="newItem.jabatan"
                />
              </div>
              <div class="mb-3">
                <label class="form-label fw-600 mb-2">Department</label>
                <select
                  class="form-control form-control-custom"
                  v-model="newItem.department"
                >
                  <option value="">Pilih Department</option>
                  <option value="Technology">Technology</option>
                  <option value="Finance">Finance</option>
                  <option value="Marketing">Marketing</option>
                  <option value="HR">HR</option>
                  <option value="Operations">Operations</option>
                </select>
              </div>
              <div class="d-flex gap-2 mt-4" style="justify-content: center">
                <button
                  type="button"
                  class="btn btn-light"
                  data-bs-dismiss="modal"
                  style="min-width: 100px; color: #666; border: 1px solid #ddd"
                >
                  Cancel
                </button>
                <button
                  type="button"
                  class="btn btn-dark"
                  @click="saveItem"
                  style="min-width: 100px"
                >
                  {{ isEditing ? "Save Changes" : "Tambah Data" }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      class="modal fade"
      id="deleteModal"
      tabindex="-1"
      aria-hidden="true"
      ref="deleteModal"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom modal-content-delete">
          <div class="modal-body d-flex flex-column align-items-center p-4">
            <div class="delete-icon mb-3">
              <i class="bi bi-trash"></i>
            </div>
            <h5 class="mb-2 fw-600">Delete Data</h5>
            <p class="text-muted mb-1" style="font-size: 0.9rem">
              {{ deleteItemName }}
            </p>
            <p
              class="text-muted"
              style="font-size: 0.85rem; margin-bottom: 1.5rem"
            >
              This action cannot be undone
            </p>
            <div class="d-flex gap-2 w-100" style="justify-content: center">
              <button
                type="button"
                class="btn btn-light"
                data-bs-dismiss="modal"
                style="min-width: 100px; color: #666; border: 1px solid #ddd"
              >
                Cancel
              </button>
              <button
                type="button"
                class="btn btn-delete"
                @click="confirmDelete"
                data-bs-dismiss="modal"
              >
                Delete Data
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { Modal } from "bootstrap";
import api from "../services/api";

export default {
  name: "ManagementAnggota",
  setup() {
    const anggota = ref([]);
    const loading = ref(true);

    const searchQuery = ref("");
    const currentPage = ref(1);
    const itemsPerPage = ref(5);
    const isEditing = ref(false);
    const editingId = ref(null);
    const newItem = ref({ nama: "", jabatan: "", department: "" });

    // Fetch members from API
    const fetchAnggota = async () => {
      try {
        const response = await api.get("/members");
        // Map API response to match our data structure
        anggota.value = response.data.data.map((item) => ({
          id: item.id,
          nama: item.name,
          jabatan: item.position,
          department: item.departement,
        }));
      } catch (error) {
        console.error("Error fetching anggota:", error);
      } finally {
        loading.value = false;
      }
    };

    const filteredAnggota = computed(() => {
      return anggota.value.filter(
        (item) =>
          item.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          item.jabatan
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase()) ||
          item.department
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase()),
      );
    });

    const totalPages = computed(() => {
      return Math.ceil(filteredAnggota.value.length / itemsPerPage.value);
    });

    let addModalInstance = null;
    let deleteModalInstance = null;

    const openAddModal = () => {
      isEditing.value = false;
      editingId.value = null;
      newItem.value = { nama: "", jabatan: "", department: "" };
      if (addModalInstance) {
        addModalInstance.show();
      }
    };

    const editItem = (item) => {
      isEditing.value = true;
      editingId.value = item.id;
      newItem.value = { ...item };
      if (addModalInstance) {
        addModalInstance.show();
      }
    };

    const saveItem = async () => {
      try {
        const payload = {
          name: newItem.value.nama,
          position: newItem.value.jabatan,
          departement: newItem.value.department,
        };

        if (isEditing.value) {
          // Update existing member
          await api.put(`/members/${editingId.value}`, payload);
          // Update local state
          const index = anggota.value.findIndex(
            (item) => item.id === editingId.value,
          );
          if (index !== -1) {
            anggota.value[index] = {
              ...anggota.value[index],
              ...newItem.value,
            };
          }
        } else {
          // Create new member
          const response = await api.post("/members", payload);
          // Add to local state
          anggota.value.push({
            id: response.data.data.id,
            ...newItem.value,
          });
        }

        // Reset form
        newItem.value = { nama: "", jabatan: "", department: "" };
        isEditing.value = false;
        editingId.value = null;

        // Close modal on success
        if (addModalInstance) {
          addModalInstance.hide();
        }
      } catch (error) {
        console.error("Error saving member:", error);
        alert(
          "Error saving member: " +
            (error.response?.data?.message || error.message),
        );
      }
    };

    onMounted(() => {
      fetchAnggota();

      // Initialize modals once on mount
      setTimeout(() => {
        const addModalEl = document.getElementById("addModal");
        const deleteModalEl = document.getElementById("deleteModal");
        if (addModalEl) {
          addModalInstance = new Modal(addModalEl, {
            backdrop: "static",
            keyboard: false,
          });
        }
        if (deleteModalEl) {
          deleteModalInstance = new Modal(deleteModalEl, {
            backdrop: "static",
            keyboard: false,
          });
        }
      }, 100);
    });
    const deleteItemId = ref(null);
    const deleteItemName = ref("");

    const deleteItem = (itemId) => {
      const item = anggota.value.find((x) => x.id === itemId);
      if (item) {
        deleteItemId.value = itemId;
        deleteItemName.value = item.nama;
        if (deleteModalInstance) {
          deleteModalInstance.show();
        }
      }
    };

    const confirmDelete = async () => {
      try {
        await api.delete(`/members/${deleteItemId.value}`);
        // Remove from local state
        anggota.value = anggota.value.filter(
          (item) => item.id !== deleteItemId.value,
        );
        if (deleteModalInstance) {
          deleteModalInstance.hide();
        }
        deleteItemId.value = null;
        deleteItemName.value = "";

        // Emit event to refresh inventory data
        window.dispatchEvent(new CustomEvent("memberDeleted"));
      } catch (error) {
        console.error("Error deleting member:", error);
        const errorMessage =
          error.response?.data?.error ||
          "Error deleting member. Please try again.";
        alert(errorMessage);
      }
    };

    const previousPage = () => {
      if (currentPage.value > 1) currentPage.value--;
    };
    const nextPage = () => {
      if (currentPage.value < totalPages.value) currentPage.value++;
    };
    const goToPage = (page) => {
      currentPage.value = page;
    };

    return {
      anggota,
      loading,
      searchQuery,
      filteredAnggota,
      currentPage,
      itemsPerPage,
      totalPages,
      newItem,
      isEditing,
      deleteItemId,
      deleteItemName,
      openAddModal,
      editItem,
      saveItem,
      deleteItem,
      confirmDelete,
      previousPage,
      nextPage,
      goToPage,
    };
  },
};
</script>

<style scoped>
* {
  font-family: "Manrope", sans-serif;
  font-weight: 600;
  font-size: 16px;
}

.table tbody td {
  padding: 1.2rem 0.75rem;
  font-weight: 400;
}

.table thead th {
  padding: 1.2rem 0.75rem;
  background-color: #fff;
  font-weight: 600;
  color: #333;
  border-bottom: 2px solid #333;
}

.table-hover tbody tr:hover {
  background-color: #f8f9fa;
}

.search-input {
  background-color: #e9ecef;
  border: 1px solid #ddd;
  border-radius: 0.375rem;
}

.form-control,
.form-select {
  border: 1px solid #ddd;
  border-radius: 0.375rem;
}

.pagination-link {
  color: #000 !important;
  border: none;
  background: none;
  padding: 0.5rem 0.7rem;
  text-decoration: none;
}

.pagination-link:hover {
  color: #000;
}

.page-item {
  margin: 0 4px;
}

.page-item.active .pagination-link {
  background-color: #000 !important;
  color: white !important;
  border-radius: 0.375rem;
  border: 1px solid #000;
}

.page-item.disabled .pagination-link {
  color: #999 !important;
  background-color: transparent;
  cursor: not-allowed;
}

.page-item:first-child.disabled .pagination-link {
  background-color: #e9ecef;
  color: #999 !important;
}

.page-item:last-child.disabled .pagination-link {
  background-color: #e9ecef;
  color: #999 !important;
}

.form-select-sm {
  border-radius: 0.375rem;
  border: 1px solid #ddd;
}

.action-btn {
  border: 1px solid #ccc;
  border-radius: 0.375rem;
  color: #666;
  padding: 0.4rem 0.5rem;
  background-color: #f5f5f5;
  cursor: pointer;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.action-btn:hover {
  background-color: #e8e8e8;
  border-color: #999;
}

.action-dropdown {
  border: 1px solid #ddd;
  border-radius: 0.375rem;
  padding: 4px;
  min-width: 100px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
  background: white;
}

.action-dropdown li {
  list-style: none;
  margin: 0;
  padding: 0;
}

.action-item {
  display: block;
  padding: 6px 12px;
  margin: 2px;
  background-color: #000 !important;
  color: white !important;
  border-radius: 0.25rem;
  text-decoration: none;
  font-size: 0.9rem;
  text-align: center;
  transition: all 0.2s;
}

.action-item:hover {
  background-color: #333 !important;
  color: white !important;
}

.modal-content-custom {
  border: 1px solid #e0e0e0;
  border-radius: 1rem;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.form-control-custom {
  border: 1px solid #ddd;
  border-radius: 0.5rem;
  background-color: #f9f9f9;
  padding: 0.75rem;
  font-size: 0.9rem;
  font-weight: 400;
  transition: all 0.2s;
}

.form-control-custom:focus {
  border-color: #999;
  background-color: #fff;
  box-shadow: none;
}

.form-label {
  font-size: 0.85rem;
  color: #333;
}

.modal-content-delete {
  border-radius: 1.5rem;
}

.delete-icon {
  width: 80px;
  height: 80px;
  background-color: #ffebee;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5rem;
  color: #d32f2f;
}

.btn-delete {
  background-color: #d32f2f;
  border: none;
  color: white;
  padding: 0.6rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.btn-delete:hover {
  background-color: #b71c1c;
  color: white;
}

.fw-600 {
  font-weight: 600;
}
</style>
